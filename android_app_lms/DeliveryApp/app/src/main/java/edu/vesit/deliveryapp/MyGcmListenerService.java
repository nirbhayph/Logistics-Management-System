package edu.vesit.deliveryapp;

import android.content.Intent;
import android.os.Bundle;

import com.google.android.gms.gcm.GcmListenerService;

public class MyGcmListenerService extends GcmListenerService
{
    @Override
    public void onMessageReceived(String from, Bundle data)
    {
        super.onMessageReceived(from, data);
        final String message = data.getString("message");
        Intent broadcastIntent = new Intent();
        broadcastIntent.setAction(MainActivity.DeliveryRequestReceiver.ACTION_REQUEST);
        broadcastIntent.addCategory(Intent.CATEGORY_DEFAULT);
        broadcastIntent.putExtra("message", message);
        sendBroadcast(broadcastIntent);
    }
}