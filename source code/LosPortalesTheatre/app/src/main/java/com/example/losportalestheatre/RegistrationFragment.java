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
 * Class name: RegistrationFragment
 * Purpose: Fragment for the registration, where the customer can register into the system
 * Date Modified: 11/07/2022 9:47 pm
 */
public class RegistrationFragment extends Fragment{
    private API api; //we initialize the API class for API related operations
    private View registrationView; ///view of the login frame

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        //We get the context for the registration view
        registrationView = inflater.inflate(R.layout.fragment_registration,container,false);

        //We get the register button and create a listener for it
        Button registerButton = registrationView.findViewById(R.id.button_register);
        registerButton.setOnClickListener(registerButtonListener);

        //We get the clear button and create a listener for it
        Button clearButton = registrationView.findViewById(R.id.button_clear);
        clearButton.setOnClickListener(clearButtonListener);

        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        // Inflate the layout for this fragment
        return registrationView;
    }

    /**
     * registerButtonListener(): button listener for register button
     */
    private final View.OnClickListener registerButtonListener = v -> {
        //disable button to avoid accidental second touch
        v.setEnabled(false);
        //start register
        startRegistration();
    };

    /**
     * clearButtonListener(): button listener for clear button
     */
    private final View.OnClickListener clearButtonListener = v -> {
        //This method clears all the views in the registration form

        //Get Edit Texts
        EditText email = registrationView.findViewById(R.id.email);
        EditText password = registrationView.findViewById(R.id.password);
        EditText pswRepeat = registrationView.findViewById(R.id.pswRepeat);
        EditText firstName = registrationView.findViewById(R.id.fName);
        EditText lastName = registrationView.findViewById(R.id.lName);
        EditText birthday = registrationView.findViewById(R.id.birthday);
        EditText phone = registrationView.findViewById(R.id.phone);

        //set them to blank
        email.setText("");
        password.setText("");
        pswRepeat.setText("");
        firstName.setText("");
        lastName.setText("");
        birthday.setText("");
        phone.setText("");

    };

    /**
     * startRegistration(): initiates the process to login the customer into their account
     *
     */
    private void startRegistration(){
        //Get the email and password edit text
        //Get Edit Texts
        EditText email = registrationView.findViewById(R.id.email);
        EditText password = registrationView.findViewById(R.id.password);
        EditText pswRepeat = registrationView.findViewById(R.id.pswRepeat);
        EditText firstName = registrationView.findViewById(R.id.fName);
        EditText lastName = registrationView.findViewById(R.id.lName);
        EditText birthday = registrationView.findViewById(R.id.birthday);
        EditText phone = registrationView.findViewById(R.id.phone);


        //Assign the values to local variables
        String emailString = email.getText().toString();
        String psw= password.getText().toString();
        String pswR = pswRepeat.getText().toString();
        String fName = firstName.getText().toString();
        String lName = lastName.getText().toString();
        String birth = birthday.getText().toString();
        String tel = phone.getText().toString();


        //send the info to the API
        api.sendRegisterInfo(emailString,psw,pswR,fName,lName,birth,tel,requireActivity());

    }
}