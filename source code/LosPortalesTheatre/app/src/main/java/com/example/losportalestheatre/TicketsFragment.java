package com.example.losportalestheatre;

import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

/**
 * Author(s): (add your name if you modify and/or add to the code)
 * Class (school): CS458
 * Class name: TicketFragment
 * Purpose: Fragment for the Tickets, where the customer can see their previous purchases
 * Date Modified: 11/07/2022 9:47 pm
 */
public class TicketsFragment extends Fragment {


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_tickets, container, false);
    }
}