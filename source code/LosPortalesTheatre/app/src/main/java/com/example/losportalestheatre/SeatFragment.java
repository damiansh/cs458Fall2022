package com.example.losportalestheatre;

import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;

import org.json.JSONException;
import org.json.JSONObject;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;


public class SeatFragment extends Fragment {
    private API api; //we initialize the API class for API related operations
    private View seatView; ///view of the login frame

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        //We get the context for the seat view
        seatView = inflater.inflate(R.layout.fragment_seat, container, false);

        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        //execute the method to generate the seat plan
        generateSeatPlan();
        // Inflate the layout for this fragment
        return seatView;
    }

    private void generateSeatPlan(){
        try{
            //get the info for the play
            JSONObject playInfo = api.getPlaySeatInfo().getValue().getJSONObject("playInfo");
            String playTitle = playInfo.getString("play_title");
            String playImageURL = "https://portales-theatre.site/images/plays/" + playInfo.getString("pURL");
            String playDesc = playInfo.getString("long_desc");
            String startTime = playInfo.getString("stime");
            String endTime = playInfo.getString("etime");

            //set the title for the support action bar
            ((AppCompatActivity)requireActivity()).getSupportActionBar().setTitle(playTitle);

            //Format date patterns
            DateTimeFormatter dateFormat = DateTimeFormatter.ofPattern("MM/dd/yyyy");
            DateTimeFormatter timeFormat = DateTimeFormatter.ofPattern("hh:mm a");
            DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");

            //Get date and start time
            LocalDateTime dateTime = LocalDateTime.parse(startTime, formatter);
            String playDate = dateTime.toLocalDate().format(dateFormat);
            startTime = dateTime.toLocalTime().format(timeFormat);

            //Get date and start time
            dateTime = LocalDateTime.parse(endTime, formatter);
            endTime = dateTime.toLocalTime().format(timeFormat);

            //create string for play time
            String playTime = String.format("%s - %s",startTime,endTime);

            //Set play data
            TextView textView = seatView.findViewById(R.id.playDesc);
            textView.setText(playDesc);
            textView = seatView.findViewById(R.id.playDate);
            textView.setText(playDate);
            textView = seatView.findViewById(R.id.playTime);
            textView.setText(playTime);

            //set the image for the play
            ImageView playImage = seatView.findViewById(R.id.seatPlayImage);
            Glide.with(this)
                    .load(playImageURL)
                    .diskCacheStrategy(DiskCacheStrategy.AUTOMATIC)
                    .error(R.drawable.placeholder)
                    .into(playImage);

        } catch (JSONException e){
            e.printStackTrace();
        }
    }


}