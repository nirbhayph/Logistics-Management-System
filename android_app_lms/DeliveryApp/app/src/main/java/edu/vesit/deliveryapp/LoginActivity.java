package edu.vesit.deliveryapp;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class LoginActivity extends AppCompatActivity
{
    private EditText input_email, input_password;
    private Button button_login, button_register;
    private Context context;
    @Override
    protected void onCreate(Bundle savedInstanceState)
    {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        input_email = (EditText) findViewById(R.id.input_email);
        input_password = (EditText) findViewById(R.id.input_password);
        button_login = (Button) findViewById(R.id.button_login);
        button_register = (Button) findViewById(R.id.button_register);
        context = this;
        button_login.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String enteredEmail = input_email.getText().toString();
                String enteredPassword = input_password.getText().toString();
                if (enteredEmail.trim().equals(""))
                    Toast.makeText(LoginActivity.this, "Please enter a email id", Toast.LENGTH_SHORT).show();
                else if (enteredPassword.trim().equals(""))
                    Toast.makeText(LoginActivity.this, "Please enter a valid password", Toast.LENGTH_SHORT).show();
                else
                    new VerifyLoginTask(context).execute(enteredEmail, enteredPassword);
            }
        });
        button_register.setOnClickListener(new View.OnClickListener()
        {
            @Override
            public void onClick(View v)
            {
                Intent i = new Intent(context, RegisterActivity.class);
                startActivity(i);
            }
        });
        SharedPreferences app_cache = getSharedPreferences("app_cache", Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = app_cache.edit();
        if (app_cache.contains("loggedIn"))
        {
            if (app_cache.getString("loggedIn", "no").equals("yes"))
            {
                input_email.setText(app_cache.getString("email", ""));
                input_password.setText(app_cache.getString("password", ""));
                button_login.performClick();
            }
        }
        else
        {
            editor.putString("loggedIn", "no");
            editor.commit();
        }
    }
}
