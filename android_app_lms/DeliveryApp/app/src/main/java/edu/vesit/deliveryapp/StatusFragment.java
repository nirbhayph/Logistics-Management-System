package edu.vesit.deliveryapp;

import android.content.Context;
import android.content.SharedPreferences;
import android.location.Location;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationListener;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationServices;

public class StatusFragment extends Fragment implements GoogleApiClient.ConnectionCallbacks, GoogleApiClient.OnConnectionFailedListener, LocationListener
{
    private GoogleApiClient mGoogleApiClient;
    private TextView current_status_display;
    private Button button_track;
    protected LocationRequest mLocationRequest;
    private String latitude, longitude;
    Context context;
    SharedPreferences app_cache;
    SharedPreferences.Editor editor;
    private String id;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState)
    {
        View rootView = inflater.inflate(R.layout.fragment_status, container, false);
        context = getActivity();
        current_status_display = (TextView) rootView.findViewById(R.id.current_status_display);
        button_track = (Button) rootView.findViewById(R.id.button_track);
        app_cache = context.getSharedPreferences("app_cache", Context.MODE_PRIVATE);
        editor = app_cache.edit();
        id = app_cache.getString("id", "");
        String status = app_cache.getString("status", "S");
        if(status.equals("S"))
            button_track.setText("Ready");
        else if(status.equals("A"))
            button_track.setText("Stop");
        buildGoogleApiClient();
        button_track.setOnClickListener(new View.OnClickListener()
        {
            @Override
            public void onClick(View v)
            {
                String status = app_cache.getString("status", "S");
                //Intent i = new Intent(context, MainMapActivity.class);
                //startActivity(i);
                if(status.equals("S"))
                {
                    mGoogleApiClient.connect();
                    new ChangeStatusTask(context).execute(id, "A");
                    button_track.setText("Stop");
                    Log.e("Started tracking : ", "aa");
                }
                else if(status.equals("A"))
                {
                    mGoogleApiClient.disconnect();
                    new ChangeStatusTask(context).execute(id, "S");
                    button_track.setText("Ready");
                    Log.e("Stopped tracking : ", "aa");
                }
            }
        });
        return rootView;
    }

    protected synchronized void buildGoogleApiClient()
    {
        mGoogleApiClient = new GoogleApiClient.Builder(context)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .addApi(LocationServices.API)
                .build();
    }

    @Override
    public void onDestroy()
    {
        if(mGoogleApiClient.isConnected())
            mGoogleApiClient.disconnect();
        super.onDestroy();
    }

    @Override
    public void onConnected(Bundle connectionHint)
    {
        mLocationRequest = LocationRequest.create();
        mLocationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);
        mLocationRequest.setInterval(4000);
        //LocationServices.FusedLocationApi.requestLocationUpdates(mGoogleApiClient, mLocationRequest, this);
    }

    @Override
    public void onLocationChanged(Location location)
    {
        latitude = location.getLatitude() + "";
        longitude = location.getLongitude() + "";
        new SendLocationTask(context).execute(id, latitude, longitude);
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
}
