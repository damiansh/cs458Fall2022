package com.example.losportalestheatre;

import android.os.Bundle;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Spinner;
import android.widget.TextView;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Locale;

/**
 * Author(s): Brian Elder and Pedro Damian Marta Rubio
 * Class (school): CS458
 * Class name: TicketFragment
 * Purpose: Fragment for the Tickets, where the customer can see their previous purchases
 * Date Modified: 11/16/2022 9:47 pm
 */
public class TicketsFragment extends Fragment {
    private API api; //we initialize the API class for API related operations
    private View ticketsView;
    private Spinner spinnerTicket;
    private String ticket;
    ArrayList<String> outputDataList  = new ArrayList<String>();

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        ticketsView = inflater.inflate(R.layout.fragment_tickets, container, false);

        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        //set cart view
        setUpTickets();

        // Inflate the layout for this fragment
        return ticketsView;
    }

    /**
     * setUpCart(): seats the views to show the cart content
     */
    private void setUpTickets(){
        //get the cart title and set it with the customer name
        TextView ticketsTitle = ticketsView.findViewById(R.id.textview_TicketsTitle);
        ticketsTitle.setText(String.format("%s's Tickets",api.getCustomerGivenName()));

        //temporal view for example
        //this is just an example, feel free to modify the layout and the variable name
        TextView temporal = ticketsView.findViewById(R.id.temporalTicketContent);
        this.spinnerTicket = (Spinner) ticketsView.findViewById(R.id.spinnerTicket);
        try{
            //get cart data
            JSONObject transactions = api.getTransactions().getValue();

            //get count and if 0 return
            int count = transactions.getInt("count");
            if(count==0){
                temporal.setText("You haven't bought tickets yet");
                return;
            }

            //get the transactions content array
            JSONArray transactionsContent = transactions.getJSONArray("transactionData");
            int lengthJsonArr = transactionsContent.length();
            //here you are going to write your code for the tickets
            //the following is just an example how to iterate through the array
            //feel free to change variables
            //remember you had to add seats using the website if the seating plan is not done in the app
            String test = "";

            //iterate with a loop
            for(int i=0;i<count;i++){
                //get the JSON Object
                JSONObject transaction = transactionsContent.getJSONObject(i);

                //get the transaction data
                int transactionNumber = Integer.parseInt(transaction.getString("transaction_id"));
                String transactionDate = transaction.getString("transaction_date");
                double orderTotal = Double.parseDouble(transaction.getString("order_total"));

                test = String.format(Locale.getDefault(),"%sTransaction Number: %d, Transaction Date: %s  Order Total: %,.2f\n",test,transactionNumber,transactionDate,orderTotal);
                ticket = "Transaction Number: " + transactionNumber + " Transaction Date: " + transactionDate +  " Order Total: " + orderTotal;
                outputDataList.add(ticket);
            }

            //example of setting content in the cart
            //temporal.setText(test);


            ArrayAdapter<String> adapter = new ArrayAdapter<String>(getContext(),
                    android.R.layout.simple_spinner_item,
                    outputDataList );
            adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
            spinnerTicket.setAdapter(adapter);

        } catch (JSONException e){
            e.printStackTrace();
        }

    }

}