package com.example.losportalestheatre;

import android.os.Bundle;

import androidx.cardview.widget.CardView;
import androidx.core.widget.NestedScrollView;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
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

/**
 * @author: Pedro Damian Marta Rubio
 * @version 1.0
 * Class (school): CS458
 * Class name: HomeFragment
 * Purpose: Fragment for the home page where the upcoming plays are displayed
 * Date Modified: 11/07/2022 9:47 pm
 */
public class HomeFragment extends Fragment {
    private API api; //we initialize the API class for API related operations
    private View homeView; ///view of the login frame
    private ProgressBar loading;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        //We get the context for the home view
        homeView = inflater.inflate(R.layout.fragment_home,container,false);


        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        //get the loading bar and set to visible
        loading = homeView.findViewById(R.id.playLoading);
        loading.setVisibility(View.VISIBLE);


        /*
         * Sets up a SwipeRefreshLayout.OnRefreshListener that is invoked when the user
         * performs a swipe-to-refresh gesture.
         */
        SwipeRefreshLayout fragmentRefresher = homeView.findViewById(R.id.fragmentRefresher);
        fragmentRefresher.setOnRefreshListener(
                () -> {
                    // This method performs the actual data-refresh operation.
                    playRefresh();
                    fragmentRefresher.setRefreshing(false);
                }
        );

        //We get the upcoming plays
        api.getUpcomingPlays(requireActivity());
        api.checkPlays().observe(getViewLifecycleOwner(), this::createPlays);



        // Inflate the layout for this fragment
        return homeView;
    }

    /**
     * createPlays(): starts the creation of the plays
     * @param upcoming string with the plays JSON
     */
    private void createPlays(String upcoming){
        //find the scroll view
        NestedScrollView scrollview = homeView.findViewById(R.id.scrollViewUpcoming);
        scrollview.removeAllViews(); //we reset it just in case to avoid crashes


        if(upcoming.equals("null") || upcoming.equals("")){
            return;
        }

        //We create the relative layout for the plays
        RelativeLayout playContainer = new RelativeLayout(requireActivity());
        RelativeLayout.LayoutParams playContainerParams = new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
        playContainer.setLayoutParams(playContainerParams);

        try{
            JSONArray upcomingPlays = new JSONArray(upcoming);
            View former = scrollview;
            for(int i=0;i<upcomingPlays.length();i++){
                JSONObject play = upcomingPlays.getJSONObject(i);
                //Extract play info from the object
                int playID = Integer.parseInt(play.getString("play_id"));
                String playTitle = play.getString("play_title");
                String playDesc = play.getString("short_desc");
                String startTime = play.getString("stime");
                String endTime = play.getString("etime");
                String playURL = play.getString("pURL");

                //Create the play card
                CardView playCard = generatePlayCard(playID, playTitle, playDesc, startTime, endTime, playURL);
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
        } catch (JSONException e){
            e.printStackTrace();
        }

        //we add the playContainer to the scroll view
        scrollview.addView(playContainer);

        //if we get here, they were loaded, so we hide the loading bar again
        loading.setVisibility(View.GONE);
    }

    /**
     * generatePlayCard(): generates the views for the play card
     * @param playID id to identify the play
     * @param playTitle title of the play
     * @param playDesc description of the play
     * @param startTime start time of the play
     * @param endTime ending time of the play
     * @param pURL url of the img
     * @return CardView returns the layout with the plays
     */
    public CardView generatePlayCard(int playID, String playTitle, String playDesc, String startTime, String endTime, String pURL){
        CardView layout = (CardView) View.inflate(requireActivity(), R.layout.play_card, null);
        //Format date patterns
        DateTimeFormatter dateFormat = DateTimeFormatter.ofPattern("MM/dd/yyyy");
        DateTimeFormatter timeFormat = DateTimeFormatter.ofPattern("h:mm a");
        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");

        //Get date and start time
        LocalDateTime dateTime = LocalDateTime.parse(startTime, formatter);
        String playDate = dateTime.toLocalDate().format(dateFormat);
        startTime = dateTime.toLocalTime().format(timeFormat);

        //Get date and end time
        dateTime = LocalDateTime.parse(endTime, formatter);
        endTime = dateTime.toLocalTime().format(timeFormat);

        //create string for play time
        String playTime = String.format("%s - %s",startTime,endTime);

        //Set play data
        TextView textView = layout.findViewById(R.id.playTitle);
        textView.setText(playTitle);
        textView = layout.findViewById(R.id.playDesc);
        textView.setText(playDesc);
        textView = layout.findViewById(R.id.playDate);
        textView.setText(playDate);
        textView = layout.findViewById(R.id.playTime);
        textView.setText(playTime);

        //We get the play button and create a listener for it
        Button playButton = layout.findViewById(R.id.playButton);
        playButton.setOnClickListener(playButtonListener);

        //change button if logged
        if(Boolean.TRUE.equals(api.isLogged().getValue())){
            playButton.setText(R.string.select_play);
            playButton.setTag(playID);

        }

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
        return layout;

    }

    /**
     * playButtonListener: listener for the play button
     */
    private final View.OnClickListener playButtonListener = playButton -> {
        if(playButton.getTag()!=null){
            api.requestPlaySeatInfo(requireActivity(), Integer.parseInt(playButton.getTag().toString()));
            return;
        }
        //if not logged in, send them to the login page
        requireActivity().getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new LoginFragment()).commit();

    };


    /**
     * playRefresh(): refresh the plays by asking again for upcoming plays
     */
    private void playRefresh(){
        //We ask again for the upcoming plays
        api.getUpcomingPlays(requireActivity());

        //we check if still logged in
        api.verifyKey(api.getCustomerKey().getValue(),requireActivity());
    }
}