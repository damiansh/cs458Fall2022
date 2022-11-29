package com.example.losportalestheatre;

import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;
import android.util.TypedValue;
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
import com.bumptech.glide.request.RequestOptions;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.Locale;


public class SeatFragment extends Fragment {
    private API api; //we initialize the API class for API related operations
    private View seatView; ///view of the login frame
    private final ArrayList<JSONObject> selectedSeats = new ArrayList<>();
    private double totalCost = 0.00; // the cost of the selection
    private Toast seatMessage;
    String seatsSelString = ""; //string of the selected seats as row letter and seat number


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        //We get the context for the seat view
        seatView = inflater.inflate(R.layout.fragment_seat, container, false);

        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        //sett listeners for add and go back buttons
        seatView.findViewById(R.id.addToCart).setOnClickListener(addToCart);
        seatView.findViewById(R.id.goBack).setOnClickListener(goBackHome);

        //execute the method to generate the seat plan
        loadViews();

        // Inflate the layout for this fragment
        return seatView;
    }

    /**
     * goBackHomeListener: listener for the go back button
     */
    private final CheckBox.OnClickListener goBackHome = button -> {
        //reset the bar
        ((AppCompatActivity)requireActivity()).getSupportActionBar().setTitle(R.string.app_name);
        //go back to home
        requireActivity().getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new HomeFragment()).commit();

    };

    /**
     * addToCartListener: listener for the add to cart button
     */
    private final CheckBox.OnClickListener addToCart = button -> {
        //cancel previous toast message
        if (seatMessage != null) {
            seatMessage.cancel();
        }
        if (selectedSeats.size() == 0){
            //display message if none seats were selected
            seatMessage = Toast.makeText(requireActivity(), "Select at least one seat", Toast.LENGTH_SHORT);
            seatMessage.show();
            return;
        }
        //send the json through the api
        api.addToCart(requireActivity(),selectedSeats.toString());
    };


    /**
     * loadViews(): loads the views for the SeatFragment
     */
    private void loadViews(){
        try{
            //get the info for the play
            JSONObject playInfo = api.getPlaySeatInfo().getValue().getJSONObject("playInfo");
            api.currentPlayID = Integer.parseInt(playInfo.getString("play_id")); //assign the current play id
            String playTitle = playInfo.getString("play_title");
            String playImageURL = api.getAPIUrl() + "/images/plays/" + playInfo.getString("pURL");
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
            RequestOptions options = new RequestOptions()
                    .placeholder(R.drawable.placeholder)
                    .error(R.drawable.placeholder)
                    .fallback(R.drawable.placeholder)
                    .diskCacheStrategy(DiskCacheStrategy.AUTOMATIC);

            Glide.with(this)
                    .load(playImageURL)
                    .apply(options)
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
                    //add tags with number, seatPrice and ticket number
                    seatBox.setTag(R.id.seatNumber,j+1);
                    seatBox.setTag(R.id.seatPrice,seat.getString("cost"));
                    seatBox.setTag(R.id.seatStatus,seat.getString("status"));
                    seatBox.setTag(R.id.seatTicketID,seat.getString("ticket_id"));
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
        //cancel previous toast message
        if (seatMessage!= null) {
            seatMessage.cancel();
        }
        CheckBox seatCheckBox = (CheckBox) seat;
        try{
            //get the tags
            int seatNumberInt = Integer.parseInt(seat.getTag(R.id.seatNumber).toString());
            String seatNumber = api.seatRowCol(seatNumberInt);
            double seatPrice = Double.parseDouble(seat.getTag(R.id.seatPrice).toString());
            int ticketID = Integer.parseInt(seat.getTag(R.id.seatTicketID).toString());
            int status = Integer.parseInt(seat.getTag(R.id.seatStatus).toString());

            //the seat is checked
            if(seatCheckBox.isChecked()){
                //we cannot add more than 10 seats
                if(selectedSeats.size()==10){
                    seatCheckBox.setChecked(false);
                    seatMessage = Toast.makeText(requireActivity(), "You can only select 10 seats each time", Toast.LENGTH_SHORT);
                    seatMessage.show();
                    return;
                }
                //We create the JSON Object with ticket id and status
                JSONObject seatJSON = new JSONObject();
                seatJSON.put("ticket_id", ticketID);
                seatJSON.put("seat_number", seatNumberInt);
                seatJSON.put("status", status);
                seatJSON.put("seatNumberLetter", seatNumber);


                //add the seat to the array list
                selectedSeats.add(seatJSON);

                //display message of selected seat
                String message = String.format(Locale.getDefault(),"Seat #: %s\nPrice: $%.2f",seatNumber,seatPrice);

                seatMessage = Toast.makeText(requireActivity(), message, Toast.LENGTH_SHORT);
                seatMessage.show();

                //add the price of the seat to the total cost
                totalCost+= seatPrice;

                //update string with seats selected
                if(seatsSelString.equals("")) seatsSelString = seatNumber;
                else seatsSelString =  seatsSelString + " · " + seatNumber;

            }
            else{//if seat is unchecked
                //we reset the string for select seats
                seatsSelString = "";
                //remove it from the array list
                for (int i = 0; i < selectedSeats.size(); i++) {
                    if(selectedSeats.get(i).getInt("ticket_id")==ticketID){
                        selectedSeats.remove(i);
                        i--; //seats shrinks, so loop needs to be adjusted
                    }
                    else{
                        //create the new string without the removed element
                        if(seatsSelString.equals("")) seatsSelString = selectedSeats.get(i).getString("seatNumberLetter");
                        else seatsSelString= seatsSelString + " · " + selectedSeats.get(i).getString("seatNumberLetter");
                    }
                }
                //remove the price of the seat into the total cost
                totalCost-=seatPrice;
            }
            //update text view with the total
            TextView total = seatView.findViewById(R.id.textview_seatTotal);
            total.setText(String.format(Locale.getDefault(),"$%.2f",totalCost));

            //update text view with the selected seats
            TextView textViewSelectedSeats = seatView.findViewById(R.id.textview_selectedSeats);
            textViewSelectedSeats.setText(seatsSelString);


        }catch (JSONException e) {
            e.printStackTrace();
        }
    };
}