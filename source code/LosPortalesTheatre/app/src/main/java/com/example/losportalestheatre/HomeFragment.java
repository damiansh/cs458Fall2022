package com.example.losportalestheatre;

import android.os.Bundle;

import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


public class HomeFragment extends Fragment {
    private API api; //we initialize the API class for API related operations
    private View homeView; ///view of the login frame


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        //We get the context for the login view
        homeView = inflater.inflate(R.layout.fragment_home,container,false);


        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        //We get the upcoming plays
        api.getUpcomingPlays(getContext());
        api.checkPlays().observe(getViewLifecycleOwner(), upcoming->{
            if(upcoming.equals("null")) upcoming = "There are not upcoming plays at this moment";
            //find the scroll view
            ScrollView scrollview = homeView.findViewById(R.id.scrollViewUpcoming);
            scrollview.removeAllViews(); //we reset it just in case to avoid crashes

            //We create the relative layout for the plays
            RelativeLayout playContainer = new RelativeLayout(requireActivity());
            RelativeLayout.LayoutParams playContainerParams = new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.WRAP_CONTENT);
            playContainer.setLayoutParams(playContainerParams);

            try{
                JSONArray upcomingPlays = new JSONArray(upcoming);
                View former = scrollview;
                for(int i=0;i<upcomingPlays.length();i++){
                    String playTitle;
                    JSONObject play = upcomingPlays.getJSONObject(i);
                    playTitle = play.getString("play_title");
                    //Text views
                    TextView textView = new TextView(requireActivity());

                    //Text params
                    RelativeLayout.LayoutParams textParams = new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.WRAP_CONTENT, RelativeLayout.LayoutParams.WRAP_CONTENT);
                    textParams.setMargins(0,16,0,0);
                    //We add rules
                    textParams.addRule(RelativeLayout.BELOW,former.getId());
                    textParams.addRule(RelativeLayout.CENTER_HORIZONTAL,1);

                    textView.setLayoutParams(textParams);
                    textView.setTextSize(20);
                    textView.setId(View.generateViewId());
                    textView.setText(playTitle);

                    textView.setText(playTitle);
                    //We add the view to the container
                    playContainer.addView(textView);

                    //we set the former text view
                    former = textView;

                }
            } catch (JSONException e){
                e.printStackTrace();
            }

            //we add the playContainer to the scroll view
            scrollview.addView(playContainer);
        });
        // Inflate the layout for this fragment
        return homeView;
    }
}