package edu.vesit.deliveryapp;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

class VerifyLoginTask extends AsyncTask<String, Void, String>
{
    private ProgressDialog pDialog;
    private Context context;
    private String email, password, id;
    public VerifyLoginTask(Context context)
    {
        this.context = context;
    }

    @Override
    protected void onPreExecute()
    {
        super.onPreExecute();
        pDialog = new ProgressDialog(context);
        pDialog.setMessage("Verifing credentials, please wait...");
        pDialog.setIndeterminate(false);
        pDialog.setCancelable(false);
        pDialog.show();
    }

    @Override
    protected String doInBackground(String... params)
    {
        if (params.length == 0)
            return null;
        HttpURLConnection urlConnection = null;
        BufferedReader reader = null;
        String response = null;
        try
        {
            URL url = new URL("http://phpscripts.mybluemix.net/deliveryapp/verify_login.php");
            urlConnection = (HttpURLConnection) url.openConnection();
            urlConnection.setRequestMethod("POST");
            String urlParameters = "email=" + params[0] + "&password=" + params[1];
            urlConnection.setDoOutput(true);
            urlConnection.setDoInput(true);
            DataOutputStream wr = new DataOutputStream(urlConnection.getOutputStream());
            wr.writeBytes(urlParameters);
            wr.flush();
            wr.close();
            urlConnection.connect();
            InputStream inputStream = urlConnection.getInputStream();
            StringBuffer buffer = new StringBuffer();
            if (inputStream == null)
            {
                Log.e("Error : ", "Input Stream is null");
                return null;
            }
            reader = new BufferedReader(new InputStreamReader(inputStream));
            String line;
            while ((line = reader.readLine()) != null)
                buffer.append(line + "\n");
            if (buffer.length() == 0)
            {
                Log.e("Error : ", "Buffer is null");
                return null;
            }
            response = buffer.toString();
        }
        catch (IOException e)
        {
            Log.e("IO Exception : ", e.getMessage());
            return null;
        }
        finally
        {
            if (urlConnection != null)
                urlConnection.disconnect();
            if (reader != null)
            {
                try
                {
                    reader.close();
                }
                catch (final IOException e)
                {
                    Log.e("Error closing stream : ", e.getMessage());
                }
            }
        }
        email = params[0];
        password = params[1];
        return response;
    }

    @Override
    protected void onPostExecute(String response)
    {
        try
        {
            if (response == null)
                Toast.makeText(context, "Network Error", Toast.LENGTH_SHORT).show();
            else
            {
                JSONObject jsonResponse = new JSONObject(response);
                String status = jsonResponse.getString("success");
                if (status.equals("0"))
                    Toast.makeText(context, "Invalid Credentials", Toast.LENGTH_SHORT).show();
                else if (status.equals("1"))
                {
                    Intent i = new Intent(context, MainActivity.class);
                    SharedPreferences app_cache = context.getSharedPreferences("app_cache", Context.MODE_PRIVATE);
                    SharedPreferences.Editor editor = app_cache.edit();
                    editor.putString("loggedIn", "yes");
                    editor.putString("email", email);
                    editor.putString("password", password);
                    id = jsonResponse.getString("id");
                    String gcm_id_status = jsonResponse.getString("gcm_id_status");
                    editor.putString("id", id);
                    editor.putString("gcm_id_status", gcm_id_status);
                    editor.commit();
                    new ChangeStatusTask(context).execute(id, "S");
                    context.startActivity(i);
                }
                else
                {
                    Toast.makeText(context, "Connection Error", Toast.LENGTH_SHORT).show();
                    Log.e("Error : ", response);
                }
            }
        }
        catch(JSONException e)
        {
            Log.e("Error in parsing : ", e.getMessage());
        }
        finally
        {
            pDialog.dismiss();
        }
    }
}