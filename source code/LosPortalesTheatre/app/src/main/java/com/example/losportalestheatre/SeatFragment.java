package com.example.losportalestheatre;

import android.app.ActionBar;
import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;

import android.util.TypedValue;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CheckBox;
import android.widget.ImageView;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Locale;


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
        loadViews();
        // Inflate the layout for this fragment
        return seatView;
    }

    /**
     * loadViews(): loads the views for the SeatFragment
     */
    private void loadViews(){
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

            //get the seating array
            JSONArray seats = api.getPlaySeatInfo().getValue().getJSONArray("seatInfo");

            //get the table layout Seat Plan
            TableLayout seatPlan = seatView.findViewById(R.id.seatPlanTableLayout);

            //loop through all the rows and seat
            int limit = 0;
            String letter = "A";
            for(int i=0;i<8;i++){
                //create new row
                TableRow currentRow = new TableRow(requireActivity());
                //set parameters for row
                currentRow.setLayoutParams(new TableLayout.LayoutParams(TableLayout.LayoutParams.WRAP_CONTENT, TableLayout.LayoutParams.WRAP_CONTENT));
                //text view for Row Letter
                TextView rowLetter = new TextView(requireActivity());
                //set parameters and values for row letter
                rowLetter.setText(letter);
                rowLetter.setTextColor(Color.WHITE); //set color to white
                rowLetter.setTypeface(Typeface.DEFAULT_BOLD); //set to bold
                rowLetter.setTextSize(TypedValue.COMPLEX_UNIT_SP, 17); //set text size to 17sp
                currentRow.addView(rowLetter);

                for(int j=limit;j<(limit+12);j++){
                    //get the current seat object
                    JSONObject seat = seats.getJSONObject(j);
                    CheckBox seatBox = new CheckBox(requireActivity());
                    seatBox.setTag(R.id.seatNumber,j+1);
                    seatBox.setTag(R.id.seatPrice,seat.getString("cost"));
                    seatBox.setButtonDrawable(R.drawable.seat_button);
                    //set listener
                    seatBox.setOnClickListener(seatCheckedListener);

                    //get the status of the seat
                    int status = Integer.parseInt(seat.getString("status"));
                    if(status==1){ // the seat is reserved
                        seatBox.setButtonDrawable(R.drawable.seat_reserved);
                        seatBox.setEnabled(false);
                    }
                    else if(status==2){ //seat is sold
                        seatBox.setEnabled(false);
                    }
                    currentRow.addView(seatBox);
                }
                //add row to the seat plan
                seatPlan.addView(currentRow);
                //increment variables
                limit+=12;
                letter = String.valueOf( (char) (letter.charAt(0) + 1));
            }
        } catch (JSONException e){
            e.printStackTrace();
        }
    }

    /**
     * seatCheckedListener: listener for the play button
     */
    private final CheckBox.OnClickListener seatCheckedListener = seat -> {
        CheckBox seatChecKBox = (CheckBox) seat;
        if(seatChecKBox.isChecked()){
            //write code when seat is checked

            //get the tags
            String seatNumber = api.seatRowCol(Integer.parseInt(seat.getTag(R.id.seatNumber).toString()));
            double seatPrice = Double.parseDouble(seat.getTag(R.id.seatPrice).toString());
            String message = String.format(Locale.getDefault(),"Seat #: %s\nPrice: $%.2f",seatNumber,seatPrice);
            Toast.makeText(requireActivity(), message, Toast.LENGTH_SHORT).show();
        }




    };


}