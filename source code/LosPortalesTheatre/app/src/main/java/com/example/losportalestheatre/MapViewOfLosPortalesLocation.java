package com.example.losportalestheatre;

import android.app.Activity;
import android.os.Bundle;


import android.view.View;
import android.widget.ImageView;

public class MapViewOfLosPortalesLocation extends Activity {


    private View _bg__mapviewoflosportales_ek2;
    private ImageView ellipse_1;
    private View ellipse_2;

    @Override
    public void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_map_view_of_los_portales_location);


        _bg__mapviewoflosportales_ek2 = (View) findViewById(R.id._bg__mapviewoflosportales_ek2);
        ellipse_1 = (ImageView) findViewById(R.id.ellipse_1);
        ellipse_2 = (View) findViewById(R.id.ellipse_2);


        //custom code goes here

    }
}