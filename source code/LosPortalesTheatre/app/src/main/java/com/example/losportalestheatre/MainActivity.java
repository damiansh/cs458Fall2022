package com.example.losportalestheatre;

import androidx.appcompat.app.ActionBarDrawerToggle;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import androidx.core.view.GravityCompat;
import androidx.drawerlayout.widget.DrawerLayout;

import android.content.SharedPreferences;
import android.os.Bundle;
import com.google.android.material.navigation.NavigationView;
import androidx.annotation.NonNull;
import androidx.lifecycle.ViewModelProvider;
import androidx.security.crypto.EncryptedSharedPreferences;
import androidx.security.crypto.MasterKeys;

import android.view.MenuItem;
import android.widget.TextView;
import android.widget.Toast;

/**
 * Author(s): Pedro Damian Marta Rubio(add your name if you modify and/or add to the code)
 * Code build with the help of https://androidknowledge.com/navigation-drawer-android-studio/#stepbystep-implementation
 * Class (school): CS458
 * Class name: MainActivity
 * Purpose: It's the Main Activity, handles the NavigationView and the fragments.
 * Date Modified: 11/07/2022 9:47 pm
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

        //get the local key
        try{
            String masterKeyAlias = MasterKeys.getOrCreate(MasterKeys.AES256_GCM_SPEC);
            SharedPreferences sharedPreferences = EncryptedSharedPreferences.create(
                    "secret_shared_prefs",
                    masterKeyAlias,
                    this,
                    EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
                    EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
            );

            //read local key
            String currentKey= sharedPreferences.getString("key", "none");
            if(!currentKey.equals("none")) api.verifyKey(currentKey,this);

        }catch(Exception e){
            e.printStackTrace();
        }

        // Create the observers which updates the UI.
        //For login status
        api.isLogged().observe(this, logged->{
            if(logged){
                //The guest menu is cleared and the one for customers is loaded
                navigationView.getMenu().clear();
                navigationView.inflateMenu(R.menu.nav_menu);
            }
            else{
                //The customer menu is cleared and the guest menu is loaded
                navigationView.getMenu().clear();
                navigationView.inflateMenu(R.menu.nav_menu_guest);
            }
        });

        //for customer key change (login)
        api.getCustomerKey().observe(this, key->{
            //shared preferences with encryption for security
            try{
                String masterKeyAlias = MasterKeys.getOrCreate(MasterKeys.AES256_GCM_SPEC);
                SharedPreferences sharedPreferences = EncryptedSharedPreferences.create(
                        "secret_shared_prefs",
                        masterKeyAlias,
                        this,
                        EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
                        EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
                );

                // storing the new key
                sharedPreferences
                        .edit()
                        .putString("key", key)
                        .apply();

            }catch(Exception e){
                e.printStackTrace();
            }

            //we send the user back to home if a key change is detected
            getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new HomeFragment()).commit();
        });


    }


    //Behavior when a item is selected in the menu, basically it is the navigation for the app
    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        switch (item.getItemId()) {
            case R.id.nav_home:
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new HomeFragment()).commit();
                break;
            case R.id.nav_cart:
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new CartFragment()).commit();
                break;
            case R.id.nav_ticket:
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new TicketsFragment()).commit();
                break;
            case R.id.nav_about:
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
        if (drawerLayout.isDrawerOpen(GravityCompat.START)) {
            drawerLayout.closeDrawer(GravityCompat.START);
        } else {
            super.onBackPressed();
        }
    }

}