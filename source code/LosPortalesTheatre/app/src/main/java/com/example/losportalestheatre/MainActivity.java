package com.example.losportalestheatre;

import static java.lang.String.*;
import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;
import android.content.SharedPreferences;
import android.os.Bundle;
import com.google.android.material.navigation.NavigationView;
import androidx.annotation.NonNull;
import androidx.fragment.app.Fragment;
import androidx.lifecycle.ViewModelProvider;
import androidx.security.crypto.EncryptedSharedPreferences;
import androidx.security.crypto.MasterKeys;
import android.view.MenuItem;


/** It's the Main Activity, handles the NavigationView, the fragments and sets up the mutable API class.
 * @author Pedro Damian Marta Rubio
 * @version 1.0
 * Code build with the help of https://androidknowledge.com/navigation-drawer-android-studio/#stepbystep-implementation
 * Class (school): CS458
 * Class name: MainActivity
 * Date Modified: 11/01/2022 9:47 pm
 */
public class MainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener  {
    private DrawerLayout drawerLayout;
    private API api; //we initialize the API class for API related operations
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        //Set navigation bar
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        drawerLayout = findViewById(R.id.drawer_layout);
        NavigationView navigationView = findViewById(R.id.nav_view);
        navigationView.setNavigationItemSelectedListener(this);
        ActionBarDrawerToggle toggle = new ActionBarDrawerToggle(this, drawerLayout, toolbar, R.string.open_nav,
                R.string.close_nav);
        drawerLayout.addDrawerListener(toggle);
        toggle.syncState();

        if (savedInstanceState == null) {
            getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new HomeFragment()).commit();
           navigationView.setCheckedItem(R.id.nav_home);
        }

        //get the ViewModel for the API
        api =  new ViewModelProvider(this).get(API.class);

        //Get and set local key
        String currentKey = getLocalKey();
        if(!currentKey.equals("none")) api.verifyKey(currentKey,this);


        // Create the observers which updates the menu.
        //For login status
        api.isLogged().observe(this, logged->{
            if(logged){
                //The guest menu is cleared and the one for customers is loaded
                navigationView.getMenu().clear();
                navigationView.inflateMenu(R.menu.nav_menu);
                navigationView.setCheckedItem(R.id.nav_home);
            }
            else{
                //The customer menu is cleared and the guest menu is loaded
                navigationView.getMenu().clear();
                navigationView.inflateMenu(R.menu.nav_menu_guest);
                navigationView.setCheckedItem(R.id.nav_home);
            }
        });

        //for customer key change (login)
        api.getCustomerKey().observe(this, this::updateKey);

    }

    /**
     * updateKey(): Method to store with encryption the key into the device
     * @param  key to be stored into the device
     */
    protected void updateKey(String key){
        //shared preferences with encryption for security
        try{
            String masterKeyAlias = MasterKeys.getOrCreate(MasterKeys.AES256_GCM_SPEC);
            SharedPreferences sharedPreferences = EncryptedSharedPreferences.create(
                    valueOf(R.string.PortalesKeyName),
                    masterKeyAlias,
                    this,
                    EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
                    EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
            );

            // storing the new key
            sharedPreferences
                    .edit()
                    .putString(valueOf(R.string.PortalesKeyIdentifier), key)
                    .apply();

        }catch(Exception e){
            e.printStackTrace();
        }

        //we send the user back to home if a key change is detected
        getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new HomeFragment()).commit();
    }

    /**
     * getLocalKey(): Access the encrypted key in the device and if found stores into the API class
     * @return currentKey the key found in the device or none if it wasn't found
     */
    protected String getLocalKey(){
        //get the local key
        try{
            String masterKeyAlias = MasterKeys.getOrCreate(MasterKeys.AES256_GCM_SPEC);

            SharedPreferences sharedPreferences = EncryptedSharedPreferences.create(
                    valueOf(R.string.PortalesKeyName),
                    masterKeyAlias,
                    this,
                    EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
                    EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
            );

            //read local key
            return  sharedPreferences.getString(valueOf(R.string.PortalesKeyIdentifier), "none");

        }catch(Exception e){
            e.printStackTrace();
        }

        return "none";
    }

    /**
     * onNavigationItemSelected(): Opens the fragment selected in the menu
     * @param item from the menu selected
     */
    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        //reset bar title
        if(!getSupportActionBar().getTitle().toString().equals(R.string.app_name))
            getSupportActionBar().setTitle(R.string.app_name);
        switch (item.getItemId()) {
            case R.id.nav_home:
                if(Boolean.TRUE.equals(api.isLogged().getValue()))  api.verifyKey(api.getCustomerKey().getValue(),this);
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new HomeFragment()).commit();
                break;
            case R.id.nav_cart:
                api.verifyKey(api.getCustomerKey().getValue(),this);
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new CartFragment()).commit();
                break;
            case R.id.nav_ticket:
                api.verifyKey(api.getCustomerKey().getValue(),this);
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new TicketsFragment()).commit();
                break;
            case R.id.nav_about:
                if(Boolean.TRUE.equals(api.isLogged().getValue()))  api.verifyKey(api.getCustomerKey().getValue(),this);
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new AboutFragment()).commit();
                break;
            case R.id.nav_login:
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new LoginFragment()).commit();
                break;
            case R.id.nav_registration:
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new RegistrationFragment()).commit();
                break;
            case R.id.nav_logout:
                //we set execute the logout method in the api
                api.logout(this);
                break;
        }
        drawerLayout.closeDrawer(GravityCompat.START);
        return true;
    }


    @Override
    public void onBackPressed() {
        //fragments to watch for
        Fragment seatPlan = getSupportFragmentManager().findFragmentByTag("SeatPlan");
        Fragment ticketViewer = getSupportFragmentManager().findFragmentByTag("ViewTickets");


        if (drawerLayout.isDrawerOpen(GravityCompat.START)) { //handles on back when drawer is open
            drawerLayout.closeDrawer(GravityCompat.START);
        }
        else if(seatPlan!=null && seatPlan.isVisible()){
            getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new HomeFragment()).commit();
            //reset the bar
            getSupportActionBar().setTitle(R.string.app_name);
        }
        else if(ticketViewer!=null && ticketViewer.isVisible()){
            getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new TicketsFragment()).commit();
            //reset the bar
            getSupportActionBar().setTitle(R.string.app_name);
        }
        else {
            super.onBackPressed();
        }
    }

}