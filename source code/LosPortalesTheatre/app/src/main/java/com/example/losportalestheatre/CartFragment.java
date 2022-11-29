package com.example.losportalestheatre;

import android.graphics.Color;
import android.os.Bundle;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;

import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.LinearLayout;
import android.widget.Button;
import android.widget.RelativeLayout;
import android.widget.TextView;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Locale;

/**
 * Author(s): Preston Feagan and Pedro Damian Marta Rubio
 * Class (school): CS458
 * Class name: CartFragment
 * Purpose: Fragment for the Cart where the customer can see the tickets they want to purchase
 * Date Modified: 11/12/2022 5:10 pm
 */

public class CartFragment extends Fragment {
    private API api; //we initialize the API class for API related operations
    private View cartView;

    Double tax=0.00;
    Double beforeTax=0.00;
    Double total=0.00;
    String timer="";


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        //Assign the view
        cartView = inflater.inflate(R.layout.fragment_cart, container, false);

        //get the ViewModel for the API
        api = new ViewModelProvider(requireActivity()).get(API.class);

        //set cart view
        setUpCart();


        // Inflate the layout for this fragment
        return cartView;
    }


    /**
     * setUpCart(): seats the views to show the cart content
     */
    private void setUpCart(){
        //get the cart title and set it with the customer name
        TextView cartTitle = cartView.findViewById(R.id.textview_CartTitle);
        cartTitle.setText(String.format("%s's Cart",api.getCustomerGivenName()));

        //temporal view for example
        //this is just an example, feel free to modify the layout and the variable name
        //TextView temporal = cartView.findViewById(R.id.temporalCartContent);
        TextView BeforeTaxText = cartView.findViewById(R.id.textView_TotalBeforeTax);
        TextView totalText = cartView.findViewById(R.id.textView_Total);
        TextView TaxText = cartView.findViewById(R.id.textView_Tax);
        //the layout on which you are working
        LinearLayout layout = (LinearLayout) cartView.findViewById(R.id.linear_layout);
        //layout.setGravity(Gravity.CENTER);
        //set the properties for button

        TextView cartItems=new TextView(getContext());
        cartItems.setLayoutParams(new LayoutParams(LayoutParams.WRAP_CONTENT, LayoutParams.WRAP_CONTENT));
        //cartItems.setGravity(Gravity.CENTER);
        cartItems.setTextSize(15);

        try{
            //get cart data
            JSONObject cart = api.getCart().getValue();

            //get count and if 0 return
            int count = cart.getInt("count");
            if(count==0){

                cartItems.setId(count);
                cartItems.setText("Empty Cart");
                //shows the cost when cart is empty
                beforeTax=0.00;
                tax=0.00;
                total =0.00;

                BeforeTaxText.setText(String.format("Total before tax: $ %.2f", beforeTax));
                TaxText.setText(String.format("Estimated tax to be collected: $ %.2f", tax));
                totalText.setText(String.format("Total: $ %.2f",total));
                layout.addView(cartItems);

                return;
            }
            //get the cart content array
            JSONArray cartContent = cart.getJSONArray("cart");

            //here you are going to write your code for the cart
            //the following is just an example how to iterate through the array
            //feel free to change variables
            //remember you had to add seats using the website if the seating plan is not done in the app
            String test = "";
            //iterate with a loop
            for(int i=0;i<count;i++){
                //get the JSON Object
                JSONObject cartItem = cartContent.getJSONObject(i);


                TextView ItemList=new TextView(getContext());
                ItemList.setLayoutParams(new LayoutParams(LayoutParams.WRAP_CONTENT, LayoutParams.WRAP_CONTENT));
                ItemList.setTextSize(15);
                ItemList.setTextColor(getResources().getColor(R.color.navyBlue));
                ItemList.setId(i);

                Button btnDlt = new Button(getContext());
                btnDlt.setLayoutParams(new LayoutParams(LayoutParams.WRAP_CONTENT, LayoutParams.WRAP_CONTENT));
                btnDlt.setTextSize(10);
                btnDlt.setText("X");
                btnDlt.setId(i);





                //get the cart item data
                int ticketNumber = Integer.parseInt(cartItem.getString("ticket_id"));
                //get the cost item data
                double cost= Double.parseDouble(cartItem.getString("cost"));

                //gets the date
                String startTime = cartItem.getString("stime");
                DateTimeFormatter formatter = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
                DateTimeFormatter dateFormat = DateTimeFormatter.ofPattern("MM/dd/yyyy");
                //DateTimeFormatter timeFormat = DateTimeFormatter.ofPattern("mm ");

                LocalDateTime date = LocalDateTime.parse(startTime,formatter);
                String playDate = date.toLocalDate().format(dateFormat);

                btnDlt.setOnClickListener(new View.OnClickListener() {
                    public void onClick(View v) {
                        layout.removeView(ItemList);
                        layout.removeView(btnDlt);

                        try {
                            int count = cart.getInt("count");
                            if(count==0){

                                cartItems.setId(count);
                                cartItems.setText("Empty Cart");
                                //shows the cost when cart is empty
                                beforeTax=0.00;
                                tax=0.00;
                                total =0.00;

                                BeforeTaxText.setText(String.format("Total before tax: $ %.2f", beforeTax));
                                TaxText.setText(String.format("Estimated tax to be collected: $ %.2f", tax));
                                totalText.setText(String.format("Total: $ %.2f",total));
                                layout.addView(cartItems);

                                return;
                            }


                            double cost = Double.parseDouble(cartItem.getString("cost"));
                            //adds up the total cost
                            beforeTax=beforeTax- cost;
                            tax=(beforeTax*0.0825);
                            total =beforeTax+tax;

                            BeforeTaxText.setText(String.format("Total before tax: $ %.2f", beforeTax));
                            TaxText.setText(String.format("Estimated tax to be collected: $ %.2f", tax));
                            totalText.setText(String.format("Total: $ %.2f",total));

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                    }
                });



                //get the seat number in the format Letter Row + seat number
                String seatNumber = api.seatRowCol(Integer.parseInt(cartItem.getString("seat_number")));
                String playTitle = cartItem.getString("play_title");
                test = String.format(Locale.getDefault(),"%s: %s %s $%.2f \n",seatNumber,playTitle,playDate,cost);



                //adds up the total cost
                beforeTax=beforeTax+ cost;
                tax=(beforeTax*0.0825);
                total =beforeTax+tax;

                BeforeTaxText.setText(String.format("Total before tax: $ %.2f", beforeTax));
                TaxText.setText(String.format("Estimated tax to be collected: $ %.2f", tax));
                totalText.setText(String.format("Total: $ %.2f",total));


                ItemList.setText(test);
                layout.addView(ItemList);
                layout.addView(btnDlt);

            }
            layout.addView(cartItems);

        } catch (JSONException e){
            e.printStackTrace();
        }

    }

}
