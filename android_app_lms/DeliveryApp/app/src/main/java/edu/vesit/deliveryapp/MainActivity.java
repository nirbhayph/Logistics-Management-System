package edu.vesit.deliveryapp;

import android.app.Dialog;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.location.Location;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.os.ResultReceiver;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GoogleApiAvailability;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationListener;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.SupportMapFragment;
import com.google.android.gms.maps.UiSettings;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.CameraPosition;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;

import org.json.JSONException;
import org.json.JSONObject;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

public class MainActivity extends AppCompatActivity implements OnMapReadyCallback, GoogleApiClient.ConnectionCallbacks, GoogleApiClient.OnConnectionFailedListener, LocationListener, View.OnClickListener
{
    private static Context context;
    private GoogleMap map;
    private GoogleApiClient mGoogleApiClient;
    private TextView current_status_display, current_address_display;
    private Button button_track;
    protected LocationRequest mLocationRequest;
    private Location mLastLocation;
    private String latitude, longitude;
    SharedPreferences app_cache;
    SharedPreferences.Editor editor;
    private AddressResultReceiver mResultReceiver;
    private DeliveryRequestReceiver deliveryRequestReceiver;
    private String id;
    private Marker managerMarker;
    SupportMapFragment mapFragment;

    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        context = MainActivity.this;
        buildGoogleApiClient();
        mapFragment = (SupportMapFragment) getSupportFragmentManager().findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);
        current_status_display = (TextView) findViewById(R.id.current_status_display);
        current_address_display = (TextView) findViewById(R.id.current_address_display);
        button_track = (Button) findViewById(R.id.button_track);
        current_address_display.setVisibility(View.GONE);
        app_cache = context.getSharedPreferences("app_cache", Context.MODE_PRIVATE);
        editor = app_cache.edit();
        id = app_cache.getString("id", "");
        new ChangeStatusTask(context).execute(id, "S");
        button_track.setText("Ready to work");
        current_status_display.setText("Logged in at " + getCurrentTime());
        mapFragment.getView().setVisibility(View.GONE);
        button_track.setOnClickListener(this);
        IntentFilter filter = new IntentFilter(DeliveryRequestReceiver.ACTION_REQUEST);
        filter.addCategory(Intent.CATEGORY_DEFAULT);
        deliveryRequestReceiver = new DeliveryRequestReceiver();
        registerReceiver(deliveryRequestReceiver, filter);

        String gcm_id_status = app_cache.getString("gcm_id_status", "1");
        if(gcm_id_status.equals("1"))
        {
            if(checkPlayServices())
            {
                Intent intent = new Intent(this, RegistrationIntentService.class);
                startService(intent);
            }
        }
    }

    protected synchronized void buildGoogleApiClient()
    {
        mGoogleApiClient = new GoogleApiClient.Builder(context)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .addApi(LocationServices.API)
                .build();
    }

    public String getCurrentTime()
    {
        DateFormat dateFormat = new SimpleDateFormat("HH:mm:ss dd/MM/yyyy");
        Date date = new Date();
        return dateFormat.format(date);
    }

    @Override
    public void onClick(View v)
    {
        String status = app_cache.getString("status", "S");
        if (status.equals("S"))
        {
            mGoogleApiClient.connect();
            new ChangeStatusTask(context).execute(id, "A");
            button_track.setText("Finished working");
            current_status_display.setText("Started working at " + getCurrentTime());
            current_address_display.setVisibility(View.VISIBLE);
            mapFragment.getView().setVisibility(View.VISIBLE);
        }
        else if (status.equals("A"))
        {
            mGoogleApiClient.disconnect();
            new ChangeStatusTask(context).execute(id, "S");
            button_track.setText("Ready to work");
            current_status_display.setText("Stopped working at " + getCurrentTime());
            current_address_display.setVisibility(View.GONE);
            mapFragment.getView().setVisibility(View.GONE);
        }
        else if (status.equals("B"))
        {
            new ChangeStatusTask(context).execute(id, "A");
            managerMarker.remove();
            button_track.setText("Finished working");
            current_status_display.setText("Finished delivery at " + getCurrentTime());
        }
    }

    @Override
    public void onDestroy()
    {
        if(mGoogleApiClient.isConnected())
            mGoogleApiClient.disconnect();
        Log.e("onDestroy", "yes");
        new ChangeStatusTask(context).execute(id, "O");
        super.onDestroy();
    }

    @Override
    public void onConnected(Bundle connectionHint)
    {
        mLocationRequest = LocationRequest.create();
        mLocationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);
        mLocationRequest.setInterval(4000);
        LocationServices.FusedLocationApi.requestLocationUpdates(mGoogleApiClient, mLocationRequest, this);
        Location lastLocation = LocationServices.FusedLocationApi.getLastLocation(mGoogleApiClient);
        if(lastLocation != null)
        {
            CameraPosition position = new CameraPosition.Builder()
                    .target(new LatLng(lastLocation.getLatitude(),lastLocation.getLongitude()))
                    .zoom(15.0f).build();
            map.animateCamera(CameraUpdateFactory.newCameraPosition(position));
        }
    }

    @Override
    public void onLocationChanged(Location location)
    {
        mLastLocation = location;
        latitude = location.getLatitude() + "";
        longitude = location.getLongitude() + "";
        new SendLocationTask(context).execute(id, latitude, longitude);
        Intent intent = new Intent(this, FetchAddressIntentService.class);
        mResultReceiver = new AddressResultReceiver(new Handler());
        intent.putExtra(Constants.RECEIVER, mResultReceiver);
        intent.putExtra(Constants.LOCATION_DATA_EXTRA, mLastLocation);
        startService(intent);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu)
    {
        getMenuInflater().inflate(R.menu.main_menu, menu);
        return true;
    }

    public boolean onOptionsItemSelected(MenuItem item)
    {
        int menuItemId = item.getItemId();
        if (menuItemId == R.id.logout)
        {
            new ChangeStatusTask(context).execute(id, "O");
            editor.putString("loggedIn", "no");
            editor.remove("email");
            editor.remove("password");
            editor.remove("id");
            editor.remove("gcm_id_status");
            editor.commit();
            finish();
            return true;
        }
        if (menuItemId == R.id.history)
        {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onConnectionFailed(ConnectionResult result)
    {
        Log.e("Error : ", "Connection failed : " + result.getErrorCode());
    }

    public void onDisconnected()
    {
        Log.e("Error : ", "Disconnected");
    }

    @Override
    public void onConnectionSuspended(int cause)
    {
        Log.e("Error : ", "Connection suspended");
        mGoogleApiClient.connect();
    }

    @Override
    public void onMapReady(GoogleMap googleMap)
    {
        map = googleMap;
        map.moveCamera( CameraUpdateFactory.newLatLngZoom(new LatLng(20.00, 76.00) , 7.0f) );
        map.setMapType(GoogleMap.MAP_TYPE_NORMAL);
        map.setMyLocationEnabled(true);
        UiSettings uiSettings = map.getUiSettings();
        uiSettings.setMapToolbarEnabled(false);
    }

    public class AddressResultReceiver extends ResultReceiver
    {
        public AddressResultReceiver(Handler handler)
        {
            super(handler);
        }

        @Override
        protected void onReceiveResult(int resultCode, Bundle resultData)
        {
            String AddressOutput = resultData.getString(Constants.RESULT_DATA_KEY);
            if(!AddressOutput.equals("Not available"))
                current_address_display.setText("Current location : \n" + AddressOutput);
        }
    }

    private boolean checkPlayServices()
    {
        GoogleApiAvailability apiAvailability = GoogleApiAvailability.getInstance();
        int resultCode = apiAvailability.isGooglePlayServicesAvailable(this);
        if (resultCode != ConnectionResult.SUCCESS)
        {
            if (apiAvailability.isUserResolvableError(resultCode))
            {
                apiAvailability.getErrorDialog(this, resultCode, 9000).show();
            }
            else
            {
                Log.e("Error : ", "This device is not supported.");
                finish();
            }
            return false;
        }
        return true;
    }

    public void createRequestDialog(String message)
    {
        try
        {
            JSONObject jsonMessage = new JSONObject(message);
            final String managerLat = jsonMessage.getString("managerLat");
            final String managerLong = jsonMessage.getString("managerLong");
            final String managerAdrs = jsonMessage.getString("managerAdrs");
            final Dialog requestDialog = new Dialog(context);
            requestDialog.setCanceledOnTouchOutside(false);
            requestDialog.setContentView(R.layout.delivery_request_popup);
            TextView delivery_address_field = (TextView) requestDialog.findViewById(R.id.delivery_address_field);
            Button accept_request_button = (Button) requestDialog.findViewById(R.id.accept_request_button);
            Button reject_request_button = (Button) requestDialog.findViewById(R.id.reject_request_button);
            reject_request_button.setOnClickListener(new View.OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    requestDialog.dismiss();
                    Toast.makeText(context, "Request rejected", Toast.LENGTH_SHORT).show();
                }
            });
            accept_request_button.setOnClickListener(new View.OnClickListener()
            {
                @Override
                public void onClick(View v)
                {
                    acceptDelivery(managerLat, managerLong, managerAdrs);
                    Toast.makeText(context, "Delivery Request Accepted !", Toast.LENGTH_SHORT).show();
                    new ChangeStatusTask(context).execute(id, "B");
                    button_track.setText("Finished Delivery");
                    requestDialog.dismiss();
                }
            });
            delivery_address_field.setText(managerAdrs);
            requestDialog.show();
        }
        catch(JSONException e)
        {
            Log.e("Parsing error : ", e.getMessage());
        }
    }

    public void acceptDelivery(String managerLat, String managerLong, String managerAdrs)
    {
        LatLng managerLocation = new LatLng(Double.parseDouble(managerLat), Double.parseDouble(managerLong));
        managerMarker = map.addMarker(new MarkerOptions()
                .position(managerLocation)
                .draggable(false)
                .icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_BLUE))
                .title("Delevery point")
                .snippet(managerAdrs));
        CameraPosition managerPosition = new CameraPosition.Builder()
                .target(new LatLng(Double.parseDouble(managerLat), Double.parseDouble(managerLong)))
                .zoom(15.0f).build();
        map.animateCamera(CameraUpdateFactory.newCameraPosition(managerPosition));
        routeToManager(managerLat, managerLong);
    }

    public void routeToManager(String managerLat, String managerLong)
    {

    }

    public class DeliveryRequestReceiver extends BroadcastReceiver
    {
        public static final String ACTION_REQUEST = "createRequestDialog";

        @Override
        public void onReceive(Context context, Intent intent)
        {
            String message = intent.getStringExtra("message");
            createRequestDialog(message);
        }
    }

    @Override
    public void onBackPressed()
    {
        moveTaskToBack(true);
    }
}