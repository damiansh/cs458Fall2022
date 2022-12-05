package com.example.losportalestheatre;

import android.os.Bundle;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.Locale;

/**
 * @author: Brian Elder and Pedro Damian Marta Rubio
 * @version 1.0
 * Class (school): CS458
 * Class name: TicketFragment
 * Purpose: Fragment for the Tickets, where the customer can see their previous purchases
 * Date Modified: 11/29/2022
 */
public class TicketsFragment extends Fragment {
    private API api; //we initialize the API class for API related operations
    private View ticketsView;
    private Spinner spinnerTicket;
    private final ArrayList<String> outputDataList  = new ArrayList<>(); //list of string for the spinner
    private final ArrayList<Integer> transactionsID  = new ArrayList<>(); //list of the transactions ids
    private  Button showButton;


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        ticketsView = inflater.inflate(R.layout.fragment_tickets, container, false);

        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        //We get the show button and create a listener for it
        showButton = ticketsView.findViewById(R.id.showTicketsButton);
        showButton.setOnClickListener(showButtonListener);

        //get the spinner
        spinnerTicket = ticketsView.findViewById(R.id.spinnerTicket);

        //set cart view
        setUpTickets();

        // Inflate the layout for this fragment
        return ticketsView;
    }

    /**
     * showButtonListener(): button listener for show button
     */
    private final View.OnClickListener showButtonListener = v -> {
        int pos = spinnerTicket.getSelectedItemPosition(); //position of selected item
        int selectedTransactionID = transactionsID.get(pos); //get the selected transaction id
        //call api method to show the tickets for current transaction
        api.requestTickets(requireActivity(),selectedTransactionID);

    };

    /**
     * setUpCart(): seats the views to show the cart content
     */
    private void setUpTickets(){
        //get the cart title and set it with the customer name
        TextView ticketsTitle = ticketsView.findViewById(R.id.textview_TicketsTitle);
        ticketsTitle.setText(String.format("%s's Tickets",api.getCustomerGivenName()));

        try{
            //get cart data
            JSONObject transactions = api.getTransactions().getValue();

            //get count and if 0 return
            int count = 0;
            if (transactions != null) {
                count = transactions.getInt("count");
            }
            if(count==0){
                //SET SPINNER AND BUTTON AS INVISIBLE AND SHOW MESSAGE OF EMPTY
                spinnerTicket.setVisibility(View.GONE);
                TextView empty = ticketsView.findViewById(R.id.emptyTickets);
                empty.setVisibility(View.VISIBLE);
                showButton.setEnabled(false);
                showButton.setVisibility(View.GONE);
                return;
            }

            //get the transactions content array
            JSONArray transactionsContent = transactions.getJSONArray("transactionData");
            //here you are going to write your code for the tickets
            //the following is just an example how to iterate through the array
            //feel free to change variables
            //remember you had to add seats using the website if the seating plan is not done in the app

            //iterate with a loop
            for(int i=0;i<count;i++){
                //get the JSON Object
                JSONObject transaction = transactionsContent.getJSONObject(i);

                //get the transaction data
                int transactionNumber = Integer.parseInt(transaction.getString("transaction_id"));
                String transactionDate = transaction.getString("transaction_date");
                double orderTotal = Double.parseDouble(transaction.getString("order_total"));

                //format transaction date and time
                //Format date patterns
                DateTimeFormatter dateFormat = DateTimeFormatter.ofPattern("MM/dd/yyyy");
                DateTimeFormatter timeFormat = DateTimeFormatter.ofPattern("h:mm a");
                DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");

                //Get date and start time
                LocalDateTime dateTime = LocalDateTime.parse(transactionDate, formatter);
                transactionDate = dateTime.toLocalDate().format(dateFormat);
                String transactionTime = dateTime.toLocalTime().format(timeFormat);

                //create text view for spinner
                String ticket = String.format(Locale.getDefault(), "Transaction #%d: $%.2f\n%s %s", transactionNumber, orderTotal, transactionDate, transactionTime);
                outputDataList.add(ticket);
                transactionsID.add(transactionNumber);

            }

            ArrayAdapter<String> adapter = new ArrayAdapter<>(getContext(),
                    R.layout.tickets_spinner,
                    outputDataList);
            spinnerTicket.setAdapter(adapter);

        } catch (JSONException e){
            e.printStackTrace();
        }

    }

}