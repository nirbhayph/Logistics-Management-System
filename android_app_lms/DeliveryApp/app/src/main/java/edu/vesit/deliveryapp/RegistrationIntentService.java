package edu.vesit.deliveryapp;

import android.app.IntentService;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Handler;
import android.util.Log;

import com.google.android.gms.gcm.GoogleCloudMessaging;
import com.google.android.gms.iid.InstanceID;

import java.io.IOException;

public class RegistrationIntentService extends IntentService
{
    public RegistrationIntentService()
    {
        super("Register");
    }

    @Override
    public void onHandleIntent(Intent intent)
    {
        try
        {
            InstanceID instanceID = InstanceID.getInstance(this);
            final String token = instanceID.getToken(getString(R.string.gcm_defaultSenderId), GoogleCloudMessaging.INSTANCE_ID_SCOPE, null);
            SharedPreferences app_cache = getSharedPreferences("app_cache", Context.MODE_PRIVATE);
            final String id = app_cache.getString("id", " ");
            final Handler mHandler = new Handler();
            new Thread(new Runnable()
            {
                @Override
                public void run()
                {
                    mHandler.post(new Runnable()
                    {
                        @Override
                        public void run()
                        {
                            new UpdateGCMIdTask().execute(id, token);
                        }
                    });
                }
            }).start();
        }
        catch(IOException e)
        {
            Log.e("Error in token : ", e.getMessage());
        }
    }
}