package com.example.losportalestheatre;

import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
/**
 * Author(s): Skyler Landess
 * Class (school): CS458
 * Class name: AboutFragment
 * Purpose: Shows information about the developer team and the website
 * Date Modified:
 */

public class AboutFragment extends Fragment {


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_about, container, false);
    }
}