package edu.vesit.deliveryapp;

import android.content.Context;
import android.os.Bundle;
import android.provider.Settings;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import java.util.Random;

public class RegisterActivity extends AppCompatActivity
{
    private Context context;
    EditText register_name, register_email, register_password, register_password_reenter, register_phone;
    Button register_delivery_boy;
    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        context = this;
        register_name = (EditText) findViewById(R.id.register_name);
        register_email = (EditText) findViewById(R.id.register_email);
        register_password = (EditText) findViewById(R.id.register_password);
        register_password_reenter = (EditText) findViewById(R.id.register_password_reenter);
        register_phone = (EditText) findViewById(R.id.register_phone);
        register_delivery_boy = (Button) findViewById(R.id.register_delivery_boy);
        register_delivery_boy.setOnClickListener(new View.OnClickListener()
        {
            @Override
            public void onClick(View v)
            {
                String enteredName = register_name.getText().toString();
                String enteredEmail = register_email.getText().toString();
                String enteredPassword = register_password.getText().toString();
                String enteredPasswordRe = register_password_reenter.getText().toString();
                String enteredPhone = register_phone.getText().toString();
                if (enteredName.trim().equals(""))
                    Toast.makeText(context, "Please enter a name", Toast.LENGTH_SHORT).show();
                else if (enteredEmail.trim().equals(""))
                    Toast.makeText(context, "Please enter a valid email", Toast.LENGTH_SHORT).show();
                else if (enteredPassword.trim().equals(""))
                    Toast.makeText(context, "Please enter a valid password", Toast.LENGTH_SHORT).show();
                else if (enteredPhone.trim().equals(""))
                    Toast.makeText(context, "Please enter a valid phone number", Toast.LENGTH_SHORT).show();
                else if (!enteredPassword.equals(enteredPasswordRe))
                    Toast.makeText(context, "The entered passwords do not match", Toast.LENGTH_SHORT).show();
                else
                {
                    String id = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
                    id += ("_" + new Random().nextInt(1000));
                    Log.e("Registered id : ", id);
                    new RegisterUserTask(context).execute(enteredName, enteredEmail, enteredPassword, enteredPhone, id);
                }
            }
        });
    }
}