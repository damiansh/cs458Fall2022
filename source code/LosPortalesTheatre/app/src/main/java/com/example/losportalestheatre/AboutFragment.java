package com.example.losportalestheatre;

import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

/**
 * A simple {@link Fragment} subclass.
 * Use the {@link AboutFragment#newInstance} factory method to
 * create an instance of this fragment.
 */
public class AboutFragment extends Fragment {
    /**
     * Author(s): (add your name if you modify and/or add to the code)
     * Class (school): CS458
     * Class name: AboutFragment
     * Purpose: Shows information about the developer team and the website
     * Date Modified: 11/07/2022 9:47 pm
     */

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_about, container, false);
    }
}