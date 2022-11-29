package com.example.losportalestheatre;

import android.content.res.Resources;
import android.graphics.Bitmap;
import android.os.Bundle;
import androidx.cardview.widget.CardView;
import androidx.core.content.ContextCompat;
import androidx.core.widget.NestedScrollView;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.request.RequestOptions;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import androidmads.library.qrgenearator.QRGContents;
import androidmads.library.qrgenearator.QRGEncoder;

/**
 * Author(s): Pedro Damian Marta Rubio
 * Class (school): CS458
 * Class name: ViewTicketsFragment
 * Purpose: Fragment see the tickets with its qr code
 * Date Modified: 11/28/2022
 */
public class ViewTicketsFragment extends Fragment {
    private API api; //we initialize the API class for API related operations
    private View ticketViewerView;
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        ticketViewerView = inflater.inflate(R.layout.fragment_view_tickets, container, false);

        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        //start showing tickets
        showTickets();

        // Inflate the layout for this fragment
        return ticketViewerView;
    }

    /**
     * showTickets(): method to show the tickets in the transaction
     */
    private void showTickets(){
        //find the scroll view
        NestedScrollView scrollview = ticketViewerView.findViewById(R.id.scrollView);
        scrollview.removeAllViews(); //we reset it just in case to avoid crashes

        try{
        //get the transaction selected
        JSONObject transaction = api.getSelectedTransaction().getValue();

        //get status
            assert transaction != null;
            int status = transaction.getInt("status");

        //if status is not 1, we do nothing
        if(status!=1){
            return;
        }

        //We create the relative layout for the plays
        RelativeLayout playContainer = new RelativeLayout(requireActivity());
        RelativeLayout.LayoutParams playContainerParams = new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
        playContainer.setLayoutParams(playContainerParams);


            JSONArray tickets = transaction.getJSONArray("transaction_data");
            View former = scrollview;
            for(int i=0;i<tickets.length();i++){
                JSONObject play = tickets.getJSONObject(i);
                //Extract play info from the object
                int ticketID = Integer.parseInt(play.getString("ticket_id"));
                String playTitle = play.getString("play_title");
                String startTime = play.getString("stime");
                String endTime = play.getString("etime");
                String playURL = play.getString("pURL");
                int seatNumber = Integer.parseInt(play.getString("seat_number"));


                //Create the play card
                CardView playCard = generateTicket(ticketID, seatNumber, playTitle, startTime, endTime, playURL);
                RelativeLayout.LayoutParams playCardParams = new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.WRAP_CONTENT);

                //generate id for the play card
                playCard.setId(View.generateViewId());

                //We add rules
                playCardParams.addRule(RelativeLayout.BELOW,former.getId());
                playCardParams.addRule(RelativeLayout.CENTER_HORIZONTAL,1);

                //set the params
                playCard.setLayoutParams(playCardParams);

                //We add the playCard to the scroll View/playContainer
                playContainer.addView(playCard);

                //we set the former text view
                former = playCard;



            }
            //we add the playContainer to the scroll view
            scrollview.addView(playContainer);
        } catch (JSONException e){
            e.printStackTrace();
        }


    }
    /**
     * generateTicket(): generates the views for the tickets
     * @param ticketID the ticket number of the current seat
     * @param seatNumber seat for the current ticket
     * @param playTitle title of the play
     * @param startTime start time of the play
     * @param endTime ending time of the play
     * @param pURL url of the img
     * @return CardView returns the layout with the plays
     */
    public CardView generateTicket(int ticketID, int seatNumber, String playTitle, String startTime, String endTime, String pURL){
        CardView layout = (CardView) View.inflate(requireActivity(), R.layout.ticket_viewer, null);
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
        TextView textView = layout.findViewById(R.id.playTitle);
        textView.setText(playTitle);
        textView = layout.findViewById(R.id.playDate);
        textView.setText(playDate);
        textView = layout.findViewById(R.id.playTime);
        textView.setText(playTime);

        //set ticket id and seat
        textView = layout.findViewById(R.id.ticketNumber);
        textView.setText("Ticket #" + ticketID);
        textView = layout.findViewById(R.id.seatNumber);
        textView.setText("Seat: " +  api.seatRowCol(seatNumber));

        //get the imageView
        ImageView playImage = layout.findViewById(R.id.playCardImage);
        String url = api.getAPIUrl() + "/images/plays/" + pURL;
        int placeholder = R.drawable.placeholder;

        //set the image to the one online
        RequestOptions options = new RequestOptions()
                .placeholder(placeholder)
                .error(placeholder)
                .fallback(R.drawable.placeholder)
                .diskCacheStrategy(DiskCacheStrategy.AUTOMATIC);

        Glide.with(this)
                .load(url)
                .apply(options)
                .into(playImage);

        //get the qr code view
        ImageView qrImage = layout.findViewById(R.id.qrCodeImage);
        // the window-manager service.
        int height = Resources.getSystem().getDisplayMetrics().heightPixels;
        int width =  Resources.getSystem().getDisplayMetrics().widthPixels;

        // generating dimension from width and height.
        int dimen = Math.min(width, height);
        dimen = dimen * 3 / 4;

        //VARIABLES FOR BITMAP AND QR
        Bitmap bitmap;
        QRGEncoder qrgEncoder;

        // setting this dimensions inside our qr code
        // encoder to generate our qr code.
        String qrContent = "Ticket for " + ticketID;
        qrgEncoder = new QRGEncoder(qrContent, null, QRGContents.Type.TEXT, dimen);

        //change black for the playcard color
        qrgEncoder.setColorBlack(ContextCompat.getColor(requireActivity(),R.color.playCardColor));
        // getting our qrcode in the form of bitmap.
        bitmap = qrgEncoder.getBitmap();
        bitmap.setHasAlpha(true);
        // the bitmap is set inside our image
        // view using .setimagebitmap method.
        qrImage.setImageBitmap(bitmap);


        return layout;

    }
}