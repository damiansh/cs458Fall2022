package com.example.losportalestheatre;

import android.app.Activity;
import android.content.Context;
import android.view.View;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;
import androidx.lifecycle.MutableLiveData;
import androidx.lifecycle.ViewModel;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.UnsupportedEncodingException;

public class API extends ViewModel {
    //Live Data Variables
    private MutableLiveData<Boolean> currentLogged; //variable to set if the user is logged
    private MutableLiveData<String> upcomingResponse; //data with upcoming plays
    private MutableLiveData<String> customerKey; //variable with the custom key assigneed to the user

    //user information
    private int customerID;
    private String customerEmail;
    private String fName;
    private String lName;

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
     * getCustomerKey(): gets the customer key
     * @return String customerKey: the customer key used for auth operations
     */
    public MutableLiveData<String> getCustomerKey(){
        if (customerKey==null){
            customerKey = new MutableLiveData<>();
            customerKey.setValue("");
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
                    if(!upcomingResponse.getValue().equals(response)) upcomingResponse.setValue(response);
                }
                , error -> upcomingResponse.setValue("Error trying to connect to the server"));

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
     * apiPOST(): sends a JSON through the post to an API
     * @param URL of the API
     * @param jsonBody the json to be sent
     * @param context the context of the app
     * @param loading the loading bar used in the view of origin
     * @param mode mode is used to select the handler
     */
    public void apiPOST(String URL, JSONObject jsonBody, Activity context, ProgressBar loading, int mode) {
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
                try {
                    return requestBody == null ? null : requestBody.getBytes("utf-8");
                } catch (UnsupportedEncodingException uee) {
                    VolleyLog.wtf("Unsupported Encoding while trying to get the bytes of %s using %s", requestBody, "utf-8");
                    return null;
                }
            }
        };
        queue.add(stringRequest);

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

        }
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


            //Regardless of outcome we get the message and status
            String message = register.getString("message");


            //Get the text view for message and set the result message
            TextView registerMessage = current.findViewById(R.id.textview_registrationMessage);
            registerMessage.setVisibility(View.VISIBLE);
            registerMessage.setText(message);

            //Set the loading bar visibility to gone
            loading.setVisibility(View.GONE);

            //Clear fields if successful
            if(status.equals("confirmedEmail")) current.findViewById(R.id.button_clear).callOnClick();



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


                //check if logging is already toggled
                if(!currentLogged.getValue()) {
                    //Update Menu email and name
                    TextView menuName = current.findViewById(R.id.textview_menu_name);
                    TextView menuEmail = current.findViewById(R.id.textview_menu_email);
                    menuName.setText(String.format("%s %s",fName,lName));
                    menuEmail.setText(customerEmail);

                    //Set login to true
                    currentLogged.setValue(Boolean.TRUE);
                    //Toast to give the welcome to the customer
                    Toast.makeText(current, welcome, Toast.LENGTH_SHORT).show();
                }


                //Successful login, so we set the customer key
                //But we first check if it is the same key, to avoid repeating
                if(!customerKey.getValue().equals(tmpKey)) customerKey.setValue(tmpKey);



            }
            else{
                //Set the login session to false if it's set as true
                if(currentLogged.getValue()) currentLogged.setValue(Boolean.FALSE);

            }
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
        menuName.setText("Los Portales Theatre Guest");
        menuEmail.setText("losportalestheatre@gmail.com");

        //finally set the custom key to none, the tmpKey as well just in case
        customerKey.setValue("none");
        tmpKey = null;

        //Set the login session to false if it's set as true
        if(currentLogged.getValue()) currentLogged.setValue(Boolean.FALSE);

    }
}
