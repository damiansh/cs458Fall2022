package com.example.losportalestheatre;

import android.os.Bundle;

import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;

/**
 * Author(s): Pedro Damian Marta Rubio (add your name if you modify and/or add to the code)
 * Class (school): CS458
 * Class name: LoginFragment
 * Purpose: Fragment for the login, where the customer can login with its credentials
 * Date Modified: 11/07/2022 9:47 pm
 */
public class LoginFragment extends Fragment{
    private API api;
    private View loginView; ///view of the login frame
    //we initialize the API class for API related operations
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        //We get the context for the login view
        loginView = inflater.inflate(R.layout.fragment_login,container,false);

        //We get the register button and create a listener for it
        Button registerButton = loginView.findViewById(R.id.button_register);
        registerButton.setOnClickListener(registerButtonListener);

        //We get the login button and create a listener for it
        Button loginButton = loginView.findViewById(R.id.button_login);
        loginButton.setOnClickListener(loginButtonListener);

        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);


        // Inflate the layout for this fragment
        return loginView;
    }
    private View.OnClickListener registerButtonListener = new View.OnClickListener() {
        public void onClick(View v) {
            requireActivity().getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new RegistrationFragment()).commit();
        }
    };

    private View.OnClickListener loginButtonListener = new View.OnClickListener() {
        public void onClick(View v) {
            //The startLogin is executed to initiate the login process
            startLogin();
        }
    };

    /**
     * startLogin(): initiates the process to login the customer into their account
     *
     */
    private void startLogin(){
        //Get the email and password edit text
        EditText email = (EditText) loginView.findViewById(R.id.email);
        EditText password = (EditText) loginView.findViewById(R.id.password);

        //Assign the values to local variables
        String emailString = email.getText().toString();
        String pswString = password.getText().toString();
        //send the info to the API
        api.sendLoginInfo(emailString,pswString, requireActivity());

    }

}