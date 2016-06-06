package edu.vesit.deliveryapp;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
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

public class RegisterUserTask extends AsyncTask<String, Void, String>
{
    private ProgressDialog pDialog;
    private Context context;

    public RegisterUserTask(Context context)
    {
        this.context = context;
    }

    @Override
    protected void onPreExecute()
    {
        super.onPreExecute();
        pDialog = new ProgressDialog(context);
        pDialog.setMessage("Registering, please wait...");
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
            URL url = new URL("http://phpscripts.mybluemix.net/deliveryapp/register_user.php");
            urlConnection = (HttpURLConnection) url.openConnection();
            urlConnection.setRequestMethod("POST");
            String urlParameters = "name=" + params[0] + "&email=" + params[1] + "&password=" + params[2] +
                    "&phone=" + params[3] + "&id=" + params[4];
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
            Log.e("Error : ", e.getMessage());
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
        return response;
    }

    @Override
    protected void onPostExecute(String response)
    {
        pDialog.dismiss();
        if (response == null)
            Toast.makeText(context, "Network Error", Toast.LENGTH_SHORT).show();
        else
        {
            try
            {
                JSONObject jsonResponse = new JSONObject(response);
                String status = jsonResponse.getString("success");
                if (status.equals("0"))
                    Toast.makeText(context, "Error in registering", Toast.LENGTH_SHORT).show();
                else if (status.equals("1"))
                {
                    Intent i = new Intent(context, LoginActivity.class);
                    context.startActivity(i);
                    Toast.makeText(context, "Registration successful", Toast.LENGTH_SHORT).show();
                }
                else
                    Toast.makeText(context, "Connection Error", Toast.LENGTH_SHORT).show();
            }
            catch (JSONException e)
            {
                Log.e("Parsing error : ", e.getMessage());
            }
        }
    }
}
