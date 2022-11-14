package com.example.losportalestheatre;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Context;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;
import androidx.lifecycle.MutableLiveData;
import androidx.lifecycle.ViewModel;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.android.material.internal.NavigationMenuItemView;

import org.json.JSONException;
import org.json.JSONObject;
import java.nio.charset.StandardCharsets;
import java.util.Locale;
import java.util.Objects;

/**
 * Author(s): Pedro Damian Marta (add your name if you modify and/or add to the code)
 * Class (school): CS458
 * Class name: API
 * Purpose: The API class handles all the operations related to the REST API
 * Date Modified: 11/07/2022 9:47 pm
 */
public class API extends ViewModel {
    //Live Data Variables
    private MutableLiveData<Boolean> currentLogged; //variable to set if the user is logged
    private MutableLiveData<String> upcomingResponse; //data with upcoming plays
    private MutableLiveData<String> customerKey; //variable with the custom key assigned to the user

    //user data
    private int customerID;
    private String customerEmail;
    private String fName;
    private String lName;
    private MutableLiveData<JSONObject> cart; //data with cart
    private MutableLiveData<JSONObject> transactions; //data with the transactions

    //tmp data
    private String postResponse; //for the temporal response of the post calls
    private String tmpKey; //a temporal and non verified key is stored here


    /**
     * getEmail(): returns the email
     * @return email
     */
    public String getEmail(){
        return customerEmail;
    }

    /**
     * getCustomerID(): returns the customer id
     * @return customerID
     */
    public int getCustomerID(){
        return customerID;
    }

    /**
     * getCustomerGivenName(): returns the customer given name
     * @return givenName
     */
    public String getCustomerGivenName(){
        return fName;
    }


    /**
     * isLogged(): returns the logged status
     * @return currentLogged
     */
    public MutableLiveData<Boolean> isLogged(){
        if (currentLogged==null){
            currentLogged = new MutableLiveData<>();
            currentLogged.setValue(Boolean.FALSE);
        }
        return currentLogged;
    }

    /**
     * checkPlays(): checks the content on the upcomingResponse
     * @return String upcomingResponse: is the json response for the getUpcomingPlays
     */
    public MutableLiveData<String> checkPlays(){
        if (upcomingResponse==null){
            upcomingResponse = new MutableLiveData<>();
            upcomingResponse.setValue("");
        }
        return upcomingResponse;
    }

    /**
     * getCart():gets the cart
     * @return JSONObject cart: is the json object with the cart content
     */
    public MutableLiveData<JSONObject> getCart(){
        if (cart==null){
            cart = new MutableLiveData<>();
            try{
                //We create the JSON Object
                JSONObject jsonBody = new JSONObject();
                jsonBody.put("status", -1);
                jsonBody.put("count", 0);

                cart.setValue(jsonBody);


            }catch (JSONException e) {
                e.printStackTrace();
            }
        }
        return cart;
    }

    /**
     * getTransactions():gets the transaction object
     * @return JSONObject transactions: is the json object with the cart content
     */
    public MutableLiveData<JSONObject> getTransactions(){
        if (transactions==null){
            transactions = new MutableLiveData<>();
            try{
                //We create the JSON Object
                JSONObject jsonBody = new JSONObject();
                jsonBody.put("status", -1);
                jsonBody.put("count", 0);

                transactions.setValue(jsonBody);

            }catch (JSONException e) {
                e.printStackTrace();
            }
        }
        return transactions;
    }

    /**
     * getCustomerKey(): gets the customer key
     * @return String customerKey: the customer key used for auth operations
     */
    public MutableLiveData<String> getCustomerKey(){
        if (customerKey==null){
            customerKey = new MutableLiveData<>();
            customerKey.setValue("none");
        }
        return customerKey;
    }



    /**
     * getUpcomingPlays(): calls the API Upcoming to get all the upcoming plays from the website
     * @param context of the activity
     */
    public void getUpcomingPlays(Context context){
        RequestQueue queue = Volley.newRequestQueue(context);
        String url ="https://portales-theatre.site/includes/api-upcoming.php";
        StringRequest stringRequest = new StringRequest(Request.Method.GET, url,
                response -> {
                //assign new value to the upcoming plays but check if is not already stored
                    if(!Objects.equals(checkPlays().getValue(), response)) checkPlays().setValue(response);
                }
                , error -> checkPlays().setValue("Error trying to connect to the server"));

        queue.add(stringRequest);

    }

    /**
     * sendLoginInfo(): send the login info through the api
     * @param email of the customer logging in
     * @param password of the customer logging in
     * @param context is the current getActivity
     */
    public void sendLoginInfo(String email, String password, Activity context){
        String URL = "https://portales-theatre.site/includes/api-login.php";
        try{
            //We create the JSON Object with the login information
            JSONObject jsonBody = new JSONObject();
            jsonBody.put("email", email);
            jsonBody.put("psw", password);

            //We get the loading bar in the login view
            ProgressBar loading = context.findViewById(R.id.loginLoading);
            apiPOST(URL, jsonBody, context, loading,0);
        }catch (JSONException e) {
            e.printStackTrace();
        }

    }

    /**
     * sendRegisterInfo(): send the registration information
     * @param email of the customer logging in
     * @param password of the customer logging in
     * @param pswR repeated password
     * @param firstName first name of the customer
     * @param lastName last name of the customer
     * @param birth birthday of the customer
     * @param context is the current getActivity
     */
    public void sendRegisterInfo(String email, String password, String pswR, String firstName, String lastName, String birth, String tel, Activity context){
        String URL = "https://portales-theatre.site/includes/api-register.php";
        try{
            //We create the JSON Object with the login information
            JSONObject jsonBody = new JSONObject();
            jsonBody.put("email", email);
            jsonBody.put("psw", password);
            jsonBody.put("pswRepeat", pswR);
            jsonBody.put("fname", firstName);
            jsonBody.put("lname", lastName);
            jsonBody.put("birthday", birth);
            jsonBody.put("phone", tel);

            //We get the loading bar from the registration
            ProgressBar loading = context.findViewById(R.id.registerLoading);
            apiPOST(URL, jsonBody, context, loading,2);
        }catch (JSONException e) {
            e.printStackTrace();
        }

    }

    /**
     * sendLoginInfo(): send the login info through the api
     * @param key of the customer to verify if session exists
     * @param context is the current getActivity
     */
    public void verifyKey(String key, Activity context){
        String URL = "https://portales-theatre.site/includes/api-verify.php";
        try{
            //We create the JSON Object with the login information
            JSONObject jsonBody = new JSONObject();
            jsonBody.put("key", key);

            //We store the temporal key
            tmpKey = key;

            //The post method is called
            apiPOST(URL, jsonBody, context, null,1);
        }catch (JSONException e) {
            e.printStackTrace();
        }

    }


    /**
     * startCartRequest(): starts the cart Request
     * @param context is the current getActivity
     */
    public void startCartRequest(Activity context){
        String URL = "https://portales-theatre.site/includes/api-cart.php";
        try{
            //We create the JSON Object with the login information
            JSONObject jsonBody = new JSONObject();
            jsonBody.put("key", getCustomerKey().getValue());
            apiPOST(URL, jsonBody, context, null,3);
        }catch (JSONException e) {
            e.printStackTrace();
        }


    }

    /**
     * startTransactionsRequest(): starts the cart Request
     * @param context is the current getActivity
     */
    public void startTransactionsRequest(Activity context){
        String URL = "https://portales-theatre.site/includes/api-transactions.php";
        try{
            //We create the JSON Object with the login information
            JSONObject jsonBody = new JSONObject();
            jsonBody.put("key", getCustomerKey().getValue());
            apiPOST(URL, jsonBody, context, null,4);
        }catch (JSONException e) {
            e.printStackTrace();
        }


    }

    /**
     * apiPOST(): sends a JSON through the post to an API
     * @param URL of the API
     * @param jsonBody the json to be sent
     * @param context the context of the app
     * @param loading the loading bar used in the view of origin
     * @param mode mode is used to select the handler
     */
    public void apiPOST(String URL, JSONObject jsonBody, Activity context, ProgressBar loading, int mode) {
        //we reset the post response just in case
        postResponse = "reset";
        //We make visible the loading view
        if(loading!=null) loading.setVisibility(View.VISIBLE);
        //We create the request using Volley
        RequestQueue queue = Volley.newRequestQueue(context);
        final String requestBody = jsonBody.toString();

        StringRequest stringRequest = new StringRequest(Request.Method.POST, URL, response -> {
            //Set the post response
            postResponse = response;
            //start handler selector
            handlerSelector(mode,context);

        }, error -> {
            //Set the loading bar visibility to gone
            if(loading!=null) loading.setVisibility(View.GONE);
            //Set the post response
            postResponse = error.toString();
        })  {
            @Override
            public byte[] getBody() {
                return requestBody.getBytes(StandardCharsets.UTF_8);
            }
        };
        queue.add(stringRequest);

    }


    /**
     * loginHandler(): handles the response from the login
     * @param current is the current getActivity
     */
    public void loginHandler(Activity current) {
        try{
            //Convert the response to JSON
            JSONObject login = new JSONObject(postResponse);

            //Get the status
            String status = login.getString("status");

            //We get the loading bar in the login view
            ProgressBar loading = current.findViewById(R.id.loginLoading);

            //Verify the status
            if(status.contains("loginError")){
                //There was an error in the login so get the message variable
                String message = login.getString("message");

                //Get the text view for message and set the error message
                TextView errorMessage = current.findViewById(R.id.textview_loginErrorMessage);
                errorMessage.setVisibility(View.VISIBLE);
                errorMessage.setText(message);

                //Set the loading bar visibility to gone
                loading.setVisibility(View.GONE);


                //get the login button and enable it again
                Button loginButton = current.findViewById(R.id.button_login);
                loginButton.setEnabled(true);
            }
            else if(status.equals("loggedIn")){
                //get the key from the JSON
                String key = login.getString("key");

                //Successful login, so we test the key
                verifyKey(key,current);

            }

        }catch(JSONException e){
            e.printStackTrace();
        }
    }
    /**
     * registerHandler(): handles the response from the login
     * @param current is the current getActivity
     */
    public void registerHandler(Activity current) {
        try{
            //Convert the response to JSON
            JSONObject register = new JSONObject(postResponse);

            //Get the status
            String status = register.getString("status");

            //We get the loading bar in the login view
            ProgressBar loading = current.findViewById(R.id.registerLoading);

            //Regardless of outcome we get the message
            String message = register.getString("message");

            //Get the text view for message and set the result message
            TextView registerMessage = current.findViewById(R.id.textview_registrationMessage);
            registerMessage.setVisibility(View.VISIBLE);
            registerMessage.setText(message);

            //Set the loading bar visibility to gone
            loading.setVisibility(View.GONE);

            //Clear fields if successful
            if(status.equals("confirmedEmail")) current.findViewById(R.id.button_clear).callOnClick();

            //make available the register button if registration is not successful
            if(status.contains("signupError")){
                //get the register button and enable it again
                Button registerButton = current.findViewById(R.id.button_register);
                registerButton.setEnabled(true);
            }


        }catch(JSONException e){
            e.printStackTrace();
        }
    }
    /**
     * verifyHandler(): handles the response from the login
     * @param current is the current getActivity
     */
    public void verifyHandler(Activity current) {
        try{
            //Convert the response to JSON
            JSONObject response = new JSONObject(postResponse);
            //Get the status
            int status = Integer.parseInt(response.getString("status")); //we assign the status as int

            //check the status, if 1 is a valid session
            if(status==1){
                //We get the other variables
                customerID = Integer.parseInt(response.getString("userID"));
                customerEmail = response.getString("userEmail");
                fName = response.getString("userFN");
                lName = response.getString("userLN");

                //Welcome message
                String welcome = String.format("Welcome %s %s",fName,lName);

                //Successful login, so we set the customer key
                //But we first check if it is the same key, to avoid repeating
                if(!Objects.equals(getCustomerKey().getValue(), tmpKey)) getCustomerKey().setValue(tmpKey);

                //check if logging is already toggled
                if(Boolean.FALSE.equals(isLogged().getValue())) {
                    //Update Menu email and name
                    TextView menuName = current.findViewById(R.id.textview_menu_name);
                    TextView menuEmail = current.findViewById(R.id.textview_menu_email);
                    menuName.setText(String.format("%s %s",fName,lName));
                    menuEmail.setText(customerEmail);

                    //get the transactions for the user
                    startTransactionsRequest(current);

                    //Set login to true
                    isLogged().setValue(Boolean.TRUE);
                    //Toast to give the welcome to the customer
                    Toast.makeText(current, welcome, Toast.LENGTH_SHORT).show();

                }

                //get the cart
                startCartRequest(current);

            }
            else{
                //Set the login session to false if it's set as true
                if(Boolean.TRUE.equals(isLogged().getValue())) logout(current);

            }
        }
        catch(JSONException e){
            e.printStackTrace();
        }


    }


    /**
     * cartHandler(): handles the response from the login
     * @param current is the current getActivity
     */
    @SuppressLint("RestrictedApi")
    public void cartHandler(Activity current) {
        try{
            //Convert the response to JSON
            JSONObject response = new JSONObject(postResponse);

            //Get the status
            int status = response.getInt("status"); //we assign the status as int

            //if the status is 0, the user is not authorized so we stop
            if(status==0){
                //We initiate the verify key process
                verifyKey(getCustomerKey().getValue(),current);
                return;
            }

            //Get cart count
            int count = response.getInt("count"); //we assign the count as int

            //if the user is authorized then assign the car
            getCart().postValue(response);
            //update the menu with the cart count
            NavigationMenuItemView cartMenu  =  current.findViewById(R.id.nav_cart);
            cartMenu.setTitle(String.format(Locale.getDefault(),"Count (%d)",count));


        }
        catch(JSONException e){
            e.printStackTrace();
        }


    }

    /**
     * transactionHandler(): handles the response from the login
     * @param current is the current getActivity
     */
    public void transactionHandler(Activity current) {
        try{
            //Convert the response to JSON
            JSONObject response = new JSONObject(postResponse);

            //Get the status
            int status = response.getInt("status"); //we assign the status as int

            //if the status is 0, the user is not authorized so we stop
            if(status==0){
                //We initiate the verify key process
                verifyKey(getCustomerKey().getValue(),current);
                return;
            }

            //if the user is authorized then assign transaction Data
            getTransactions().postValue(response);

        }
        catch(JSONException e){
            e.printStackTrace();
        }


    }

    /**
     * logout(): logouts the user
     * @param current is the current getActivity
     */
    public void logout(Activity current){
        //Reset the user variables
        customerID = 0;
        customerEmail = null;
        fName = null;
        lName = null;

        //Reset the menu name and email
        TextView menuName = current.findViewById(R.id.textview_menu_name);
        TextView menuEmail = current.findViewById(R.id.textview_menu_email);
        menuName.setText(R.string.GuestNamePlaceHolder);
        menuEmail.setText(R.string.losportales_email);

        //finally set the custom key to none, the tmpKey as well just in case
        getCustomerKey().setValue("none");
        tmpKey = null;

        //Set the login session to false if it's set as true
        if(Boolean.TRUE.equals(isLogged().getValue())) isLogged().setValue(Boolean.FALSE);

        //Inform the user with a toast message
        Toast.makeText(current, "Logged out!", Toast.LENGTH_SHORT).show();
    }


    /**
     * handlerSelector(): assigns the correct handler depending on the mode
     * @param mode 0=login,
     * @param current is the current getActivity
     */
    public void handlerSelector(int mode, Activity current) {
        switch (mode) {
            case 0: //Login Handler
                loginHandler(current);
                break;
            case 1: //Key verification handler
                verifyHandler(current);
                break;
            case 2: //Registration mode
                registerHandler(current);
                break;
            case 3: //Cart Mode
                cartHandler(current);
                break;
            case 4: //Transactions Mode
                transactionHandler(current);
                break;
        }
    }


    /**
     * seatRowCol(): converts the seatNumber to the format Row Letter Col Number {A1,B2,ETC.}
     * @param number the number of the seat from 1 to 96
     * @return seatRowCol Format
     */
    public String seatRowCol(int number) {
        if(number<=12){
            return "A" + number;
        }

        else if(number<=24){
            int seatN  = number-(12);
            return "B" + seatN;

        }

        else if(number<=36){
            int seatN  = number-(12*2);
            return "C" + seatN;
        }

        else if(number<=48){
            int seatN  = number-(12*3);
            return "D" + seatN;

        }

        else if(number<=60){
            int seatN  = number-(12*4);
            return "E" + seatN;
        }

        else if(number<=72){
            int seatN  = number-(12*5);
            return "F" + seatN;
        }

        else if(number<=84){
            int seatN  = number-(12*6);
            return "G" + seatN;
        }

        else if(number<=96){
            int seatN = number-(12*7);
            return "H" + seatN;
        }

        return "Invalid seat";
    }

}
