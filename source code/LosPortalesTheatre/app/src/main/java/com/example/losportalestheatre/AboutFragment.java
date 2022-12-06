package com.example.losportalestheatre;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;

/** Shows information about the developer team and the website
 * @author: Skyler Landess and Pedro Damian Marta Rubio
 * @version 1.0
 * Class (school): CS458
 * Class name: AboutFragment
 * Date Modified: 12/5/2022
 */

public class AboutFragment extends Fragment {
    private View aboutView;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        aboutView = inflater.inflate(R.layout.fragment_about, container, false);


        //We get the register button and create a listener for it
        Button openMap = aboutView.findViewById(R.id.mapBtn);
        openMap.setOnClickListener(openMapListener);

        return aboutView;
    }

    /**
     * openMapListener(): button listener for open map button
     */
    private final View.OnClickListener openMapListener = v -> {
        //execute open map method
        openMap();
    };


    /**
     * openMap(): Starts an intent with google maps
     */
    public void openMap(){
        // Create a Uri from an intent string. Use the result to create an Intent.
        String address = getResources().getString(R.string.losportales_address);
        Uri gmmIntentUri = Uri.parse("geo:0,0?q=" + address);

        // Create an Intent from gmmIntentUri. Set the action to ACTION_VIEW
        Intent mapIntent = new Intent(Intent.ACTION_VIEW, gmmIntentUri);
        // Make the Intent explicit by setting the Google Maps package
        mapIntent.setPackage("com.google.android.apps.maps");

        // Attempt to start an activity that can handle the Intent
        startActivity(mapIntent);
    }
}