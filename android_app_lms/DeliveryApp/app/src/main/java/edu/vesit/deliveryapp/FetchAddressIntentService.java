package edu.vesit.deliveryapp;

import android.app.IntentService;
import android.content.Intent;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.os.Bundle;
import android.support.v4.os.ResultReceiver;
import android.util.Log;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

public class FetchAddressIntentService extends IntentService
{
    protected ResultReceiver mReceiver;

    public FetchAddressIntentService()
    {
        super("FetchAddress");
    }

    @Override
    protected void onHandleIntent(Intent intent)
    {
        String errorMessage = "";
        Geocoder geocoder = new Geocoder(this, Locale.getDefault());
        Location location = intent.getParcelableExtra(Constants.LOCATION_DATA_EXTRA);
        mReceiver = intent.getParcelableExtra(Constants.RECEIVER);
        List<Address> addresses = null;
        try
        {
            addresses = geocoder.getFromLocation(location.getLatitude(), location.getLongitude(), 1);
        }
        catch (IOException e)
        {
            errorMessage = "Not available";
            Log.e("Error : ", e.getMessage());
        }
        catch (IllegalArgumentException e)
        {
            errorMessage = "Not available";
            Log.e("Error : ", e.getMessage());
        }

        if (addresses == null || addresses.size()  == 0)
        {
            if (errorMessage.isEmpty())
            {
                errorMessage = "Not available";
                Log.e("Error : ", "No address found");
            }
            deliverResultToReceiver(Constants.FAILURE_RESULT, errorMessage);
        }
        else
        {
            Address address = addresses.get(0);
            ArrayList<String> addressFragments = new ArrayList<String>();
            for(int i = 0; i < address.getMaxAddressLineIndex(); i++)
                addressFragments.add(address.getAddressLine(i));
            String addressString = "";
            for(String i : addressFragments)
                addressString += (" " + i);
            deliverResultToReceiver(Constants.SUCCESS_RESULT, addressString);
        }
    }

    private void deliverResultToReceiver(int resultCode, String message)
    {
        Bundle bundle = new Bundle();
        bundle.putString(Constants.RESULT_DATA_KEY, message);
        mReceiver.send(resultCode, bundle);
    }
}