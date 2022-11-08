package com.example.losportalestheatre;

import android.os.Bundle;

import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.TextView;
import android.widget.Toast;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;


/**
 * Author(s): Pedro Damian Marta (add your name if you modify and/or add to the code)
 * Class (school): CS458
 * Class name: HomeFragment
 * Purpose: Fragment for the home page where the upcoming plays are displayed
 * Date Modified: 11/07/2022 9:47 pm
 */

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
        api.getUpcomingPlays(requireActivity());
        api.checkPlays().observe(getViewLifecycleOwner(), upcoming->{
            //get the loading bar and set to visible
            ProgressBar loading = homeView.findViewById(R.id.playLoading);
            loading.setVisibility(View.VISIBLE);

            //find the scroll view
            ScrollView scrollview = homeView.findViewById(R.id.scrollViewUpcoming);
            scrollview.removeAllViews(); //we reset it just in case to avoid crashes


            if(upcoming.equals("null")){
                loading.setVisibility(View.GONE);
                return;
            }
            //We create the relative layout for the plays
            RelativeLayout playContainer = new RelativeLayout(requireActivity());
            RelativeLayout.LayoutParams playContainerParams = new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.WRAP_CONTENT);
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
                    RelativeLayout playCard = generatePlayCard(playID, playTitle, playDesc, startTime, endTime, playURL);
                    RelativeLayout.LayoutParams playCardParams = new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.WRAP_CONTENT, RelativeLayout.LayoutParams.WRAP_CONTENT);
                    playCardParams.setMargins(0,16,0,0);

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
                //if we get here, they were loaded, so we hide the loading bar again
                loading.setVisibility(View.GONE);

            } catch (JSONException e){
                e.printStackTrace();
            }

            //we add the playContainer to the scroll view
            scrollview.addView(playContainer);
        });
        // Inflate the layout for this fragment
        return homeView;
    }

    public RelativeLayout generatePlayCard(int playID, String playTitle, String playDesc, String startTime, String endTime, String pURL){
        LayoutInflater inflater = LayoutInflater.from(requireActivity());
        RelativeLayout layout = (RelativeLayout) inflater.inflate(R.layout.play_card, null, false);
        //Format date patterns
        DateTimeFormatter dateFormat = DateTimeFormatter.ofPattern("MM/dd/yyyy");
        DateTimeFormatter timeFormat = DateTimeFormatter.ofPattern("hh:mm a");
        DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");

        //Get date and start time
        LocalDateTime dateTime = LocalDateTime.parse(startTime, formatter);
        String playDate = dateTime.toLocalDate().format(dateFormat).toString();
        startTime = dateTime.toLocalTime().format(timeFormat).toString();

        //Get date and start time
        dateTime = LocalDateTime.parse(endTime, formatter);
        endTime = dateTime.toLocalTime().format(timeFormat).toString();

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
            playButton.setText("Select play");
            playButton.setTag(playTitle);

        }

        //get the imageView
        ImageView playImage = layout.findViewById(R.id.playCardImage);
        String url = "https://portales-theatre.site/images/plays/" + pURL;

        int placeholder = requireActivity().getResources().getIdentifier("placeholder", "drawable", requireActivity().getPackageName());
        //set the image to the one online
        Glide.with(this)
                .load(url)
                .diskCacheStrategy(DiskCacheStrategy.AUTOMATIC)
                .error(placeholder)
                .into(playImage);



        return layout;

    }

    private View.OnClickListener playButtonListener = new View.OnClickListener() {
        public void onClick(View playButton) {
            if(playButton.getTag()!=null){
                Toast.makeText(requireActivity(), playButton.getTag().toString(), Toast.LENGTH_SHORT).show();
                return;
            }
            //if not logged in, send them to the login page
            requireActivity().getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new LoginFragment()).commit();

        }
    };
}