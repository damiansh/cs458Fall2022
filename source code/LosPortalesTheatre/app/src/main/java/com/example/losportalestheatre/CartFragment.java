package com.example.losportalestheatre;

import android.content.DialogInterface;
import android.os.Bundle;
import androidx.appcompat.app.AlertDialog;
import androidx.cardview.widget.CardView;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.text.Html;
import android.widget.RelativeLayout;
import android.widget.TextView;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Locale;

/** Fragment for the Cart where the customer can see the tickets they want to purchase
 * @author Preston Feagan and Pedro Damian Marta Rubio
 * @version 1.0
 * Class (school): CS458
 * Class name: CartFragment
 * Date Modified: 12/3/2022 5:37 pm
 */
public class CartFragment extends Fragment {
    private API api; //we initialize the API class for API related operations
    private View cartView;

    double tax=0.00;
    double beforeTax=0.00;
    double total=0.00;


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        //Assign the view
        cartView = inflater.inflate(R.layout.fragment_cart, container, false);

        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        //set cart view
        setUpCart();


        //We get the checkout button and create a listener for it
        Button registerButton = cartView.findViewById(R.id.Button_Checkout);
        registerButton.setOnClickListener(checkoutListener);



        // Inflate the layout for this fragment
        return cartView;
    }

    /**
     * checkoutListener(): button listener for checkout button
     */
    protected final View.OnClickListener checkoutListener = v -> {
        //disable button to avoid accidental second touch
        v.setEnabled(false);

        //Create Alert
        AlertDialog alertMessage = new AlertDialog.Builder(requireActivity())
                .create();
        alertMessage.setCancelable(false);
        alertMessage.setTitle("Confirm Purchase");
        String message = "Are you you sure you want to purchase these tickets?<br><br>They will be charged to your google pay account.";
        alertMessage.setMessage(Html.fromHtml(message, Html.FROM_HTML_MODE_COMPACT));
        alertMessage.setButton(DialogInterface.BUTTON_POSITIVE,"Yes", (dialog, which) -> {
            //initiate the checkout process
            api.checkOutCart(requireActivity(),total);
            alertMessage.cancel();
        });
        alertMessage.setButton(DialogInterface.BUTTON_NEGATIVE,"No", (dialog, which) -> {
            //enable the button again
            v.setEnabled(true);
            //close
            alertMessage.cancel();

        });
        alertMessage.show();


    };


    /**
     * setUpCart(): seats the views to show the cart content
     */
    protected void setUpCart(){
        //get the cart title and set it with the customer name
        TextView cartTitle = cartView.findViewById(R.id.textview_CartTitle);
        cartTitle.setText(String.format("%s's Cart",api.getCustomerGivenName()));

        try{
            //get cart data
            JSONObject cart = api.getCart().getValue();

            //get count and if 0 return
            assert cart != null;
            int count = cart.getInt("count");
            if(count==0){

                //shows the cost when cart is empty
                beforeTax=0.00;
                tax=0.00;
                total =0.00;

                //disable the button if empty
                cartView.findViewById(R.id.Button_Checkout).setEnabled(false);
                return;
            }
            //show relative layout with the data and hide the empty cart text view
            cartView.findViewById(R.id.cartScrollView).setVisibility(View.VISIBLE);
            cartView.findViewById(R.id.emptyCart).setVisibility(View.GONE);

            //get the textview
            TextView BeforeTaxText = cartView.findViewById(R.id.textView_TotalBeforeTax);
            TextView totalText = cartView.findViewById(R.id.textView_Total);
            TextView TaxText = cartView.findViewById(R.id.textView_Tax);

            //get the cart content array
            JSONArray cartContent = cart.getJSONArray("cart");

            //find the container for tickets
            RelativeLayout ticketsContainer = cartView.findViewById(R.id.ticketsContainer);
            ticketsContainer.removeAllViews(); //we reset it just in case to avoid crashes
            int former = 0;
            //iterate with a loop
            for(int i=0;i<count;i++){
                //get the JSON Object
                JSONObject cartItem = cartContent.getJSONObject(i);

                //get the cart item data
                int ticketNumber = Integer.parseInt(cartItem.getString("ticket_id"));
                double cost= Double.parseDouble(cartItem.getString("cost"));
                //gets the date
                String startTime = cartItem.getString("stime");
                String endTime = cartItem.getString("etime");


                //get the seat number in the format Letter Row + seat number
                String seatNumber = api.seatRowCol(Integer.parseInt(cartItem.getString("seat_number")));
                String playTitle = cartItem.getString("play_title");

                //Create the ticket items card
                CardView ticketCard = generateTicket(ticketNumber, playTitle, startTime, endTime, seatNumber, cost);
                RelativeLayout.LayoutParams ticketsParams = new RelativeLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.WRAP_CONTENT);

                //generate id for the play card
                ticketCard.setId(View.generateViewId());

                //We add rules
                if(former!=0)
                    ticketsParams.addRule(RelativeLayout.BELOW,former);
                ticketsParams.addRule(RelativeLayout.CENTER_HORIZONTAL,1);


                //set the params
                ticketCard.setLayoutParams(ticketsParams);

                //We add the playCard to the scroll View/playContainer
                ticketsContainer.addView(ticketCard);

                //we set the former text view
                former = ticketCard.getId();


                //adds up the total cost
                beforeTax=beforeTax+ cost;

            }
            //calculate tax and total
            tax=(beforeTax*0.0825);
            total =beforeTax+tax;

            BeforeTaxText.setText(String.format(Locale.getDefault(),"Total before tax: $ %.2f", beforeTax));
            TaxText.setText(String.format(Locale.getDefault(),"Estimated tax to be collected: $ %.2f", tax));
            totalText.setText(String.format(Locale.getDefault(),"Total: $ %.2f",total));

        } catch (JSONException e){
            e.printStackTrace();
        }

    }

    /**
     * generateTicket(): generates the views for the items in the cart
     * @param ticketID id to identify cart item
     * @param playTitle title of the play
     * @param startTime start time of the play
     * @param endTime ending time of the play
     * @param seat the seat number in the format ROW letter and col number
     * @param cost the cost of that particular seat
     * @return CardView returns the layout with the tickets
     */
    protected CardView generateTicket(int ticketID, String playTitle, String startTime, String endTime, String seat, double cost){
        CardView layout = (CardView) View.inflate(requireActivity(), R.layout.cart_items, null);
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

        //Set ticket id
        TextView textView = layout.findViewById(R.id.ticketNumber);
        textView.setText(String.format(Locale.getDefault(),"Ticket #%d",ticketID));

        //Set play title
        textView = layout.findViewById(R.id.playTitle);
        textView.setText(playTitle);

        //set seat
        textView = layout.findViewById(R.id.seatNumber);
        textView.setText(String.format(Locale.getDefault(),"Seat %s",seat));

        //set ticket/seat  cost
        textView = layout.findViewById(R.id.seatPrice);
        textView.setText(String.format(Locale.getDefault(),"$%.2f",cost));

        //set play date
        textView = layout.findViewById(R.id.playDate);
        textView.setText(playDate);
        //set play time
        textView = layout.findViewById(R.id.playTime);
        textView.setText(playTime);

        //We get the delete button and create a listener for it
        Button deleteButton = layout.findViewById(R.id.deleteButton);
        deleteButton.setOnClickListener(deleteListener);
        deleteButton.setTag(ticketID); //set the ticket id as tag



        return layout;

    }

    /**
     * deleteListener: listener for the delete button
     */
    protected final View.OnClickListener deleteListener = deleteButton -> {
        if(deleteButton.getTag()!=null){
            api.deleteFromCart(requireActivity(), Integer.parseInt(deleteButton.getTag().toString()));
        }

    };


}