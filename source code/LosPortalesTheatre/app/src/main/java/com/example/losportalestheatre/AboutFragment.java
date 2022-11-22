package com.example.losportalestheatre;

import android.content.Intent;
import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

/**
 * Author(s): Skyler Landess
 * Class (school): CS458
 * Class name: AboutFragment
 * Purpose: Shows information about the developer team and the website
 * Date Modified:
 */

public class AboutFragment extends Fragment {
    private View AboutView; ///view of the login frame
    private View _bg__aboutus_fragment_ek2;
    private ImageView footerblackrectangle;
    private ImageView los_portales_logos_white_2;
    private TextView phone__555_575_5555;
    private TextView website__los_portales_theatre;
    private TextView email__losportalestheatre_gmail_com;
    private TextView ___2022_copyright;
    private ImageView los_portales_logos_white_1;
    private ImageView brianbluerect;
    private View grayrectanglebrian_7;
    private ImageView brian_elder_enmu_1;
    private TextView brian_is_a_computer_science_student_at_enmu__he_enjoys_learning_new_techniques_and_technologies_and_constantly_strives_to_improve_his_abilities__the_los_portales_theatre_app_has_been_a_significant_project_and_has_pushed_him_to_develop_new_skill_sets_;
    private ImageView prestonbluerect;
    private ImageView prestonpicture;
    private View rectanglepreston6;
    private TextView preston_is_a_computer_science_major_going_to_school_at_enmu__he_is_interested_in_cyber_security__he_is_always_looking_for_more_knowledge_and_information_on_it__the_los_portales_theater_app_is_the_first_app_he_has_helped_build_from_the_ground_up_with_his_team_;
    private View skylerbluerect;
    private View rectangle_5;
    private ImageView skyler_landess_university_picture_1;
    private TextView skyler_landess_is_an_avid_computer_enthusiast_who_enjoys_creating_apps_and_web_applications__he_is_pursuing_a_computer_science_degree_at_eastern_new_mexico_university__skyler_plans_to_learn_more_about_hardening_api_vulnerabilities_by_studying_the_apis_developed_on_this_mobile_app___i_am_passionate_about_electronics_and_cybersecurity_through_technology__and_everyone_is_a_social_engineer__;
    private ImageView damianbluerect;
    private View rectangle_4;
    private TextView damian_marta_is_an_undergraduate_student_majoring_in_computer_science_at_eastern_new_mexico_university__he_is_interested_in_data_analysis_and_web_and_app_development__he_is_always_eager_to_learn_new_stuff_or_ways_to_do_what_he_already_knows_to_be_more_efficient__los_portales_theatre_app_is_the_first_app_he_has_had_the_opportunity_to_build_from_zero__therefore__he_values_the_great_experience_of_working_on_a_project_like_this_one_;
    private ImageView damian_2;
    private View rectangle_3;
    private TextView the_developement_team;
    private View whiterectangle_;
    private TextView maplosportales;
    private View rectangle_2;
    private TextView directions;
    private ImageView _mapicon;
    private View rectangle_2_ek1;
    private View grayfoundedrectangle;
    private TextView founded_in_1942_by_bob_and_lounette_cox__a_theatrical_and_comedic_production_business_in_portales__new_mexico__performers_created_slapstick_stand_up__talent_shows__and_musical_comedies__the_portales_players_disbanded_after_world_war_ii_broke_out__but_many_of_its_former_members_regrouped_in_1947_to_create_the_los_portales_theatre_company_;
    private ImageView community_theatre_1;
    private View line_1;
    private View line_2;
    private View line_4;


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        //assign the About View


        AboutView = inflater.inflate(R.layout.fragment_about, container, false);

        //insert your code before this line, do not delete the AboutView global variable or the return statement
        //If part of your code has findByViewId to access the views in the xml layout, add AboutView before calling the method. A
        //Example AboutView.findByViewId
        _bg__aboutus_fragment_ek2 = (View) AboutView.findViewById(R.id._bg__aboutus_fragment_ek2);
        footerblackrectangle = (ImageView) AboutView.findViewById(R.id.footerblackrectangle);
        los_portales_logos_white_2 = (ImageView) AboutView.findViewById(R.id.los_portales_logos_white_2);
        phone__555_575_5555 = (TextView) AboutView.findViewById(R.id.phone__555_575_5555);
        website__los_portales_theatre = (TextView) AboutView.findViewById(R.id.website__los_portales_theatre);
        email__losportalestheatre_gmail_com = (TextView) AboutView.findViewById(R.id.email__losportalestheatre_gmail_com);
        ___2022_copyright = (TextView) AboutView.findViewById(R.id.___2022_copyright);
        los_portales_logos_white_1 = (ImageView) AboutView.findViewById(R.id.los_portales_logos_white_1);
        brianbluerect = (ImageView) AboutView.findViewById(R.id.brianbluerect);
        grayrectanglebrian_7 = (View) AboutView.findViewById(R.id.grayrectanglebrian_7);
        brian_elder_enmu_1 = (ImageView) AboutView.findViewById(R.id.brian_elder_enmu_1);
        brian_is_a_computer_science_student_at_enmu__he_enjoys_learning_new_techniques_and_technologies_and_constantly_strives_to_improve_his_abilities__the_los_portales_theatre_app_has_been_a_significant_project_and_has_pushed_him_to_develop_new_skill_sets_ = (TextView) AboutView.findViewById(R.id.brian_is_a_computer_science_student_at_enmu__he_enjoys_learning_new_techniques_and_technologies_and_constantly_strives_to_improve_his_abilities__the_los_portales_theatre_app_has_been_a_significant_project_and_has_pushed_him_to_develop_new_skill_sets_);
        prestonbluerect = (ImageView) AboutView.findViewById(R.id.prestonbluerect);
        prestonpicture = (ImageView) AboutView.findViewById(R.id.prestonpicture);
        rectanglepreston6 = (View) AboutView.findViewById(R.id.rectanglepreston6);
        preston_is_a_computer_science_major_going_to_school_at_enmu__he_is_interested_in_cyber_security__he_is_always_looking_for_more_knowledge_and_information_on_it__the_los_portales_theater_app_is_the_first_app_he_has_helped_build_from_the_ground_up_with_his_team_ = (TextView) AboutView.findViewById(R.id.preston_is_a_computer_science_major_going_to_school_at_enmu__he_is_interested_in_cyber_security__he_is_always_looking_for_more_knowledge_and_information_on_it__the_los_portales_theater_app_is_the_first_app_he_has_helped_build_from_the_ground_up_with_his_team_);
        skylerbluerect = (View) AboutView.findViewById(R.id.skylerbluerect);
        rectangle_5 = (View) AboutView.findViewById(R.id.rectangle_5);
        skyler_landess_university_picture_1 = (ImageView) AboutView.findViewById(R.id.skyler_landess_university_picture_1);
        skyler_landess_is_an_avid_computer_enthusiast_who_enjoys_creating_apps_and_web_applications__he_is_pursuing_a_computer_science_degree_at_eastern_new_mexico_university__skyler_plans_to_learn_more_about_hardening_api_vulnerabilities_by_studying_the_apis_developed_on_this_mobile_app___i_am_passionate_about_electronics_and_cybersecurity_through_technology__and_everyone_is_a_social_engineer__ = (TextView) AboutView.findViewById(R.id.skyler_landess_is_an_avid_computer_enthusiast_who_enjoys_creating_apps_and_web_applications__he_is_pursuing_a_computer_science_degree_at_eastern_new_mexico_university__skyler_plans_to_learn_more_about_hardening_api_vulnerabilities_by_studying_the_apis_developed_on_this_mobile_app___i_am_passionate_about_electronics_and_cybersecurity_through_technology__and_everyone_is_a_social_engineer__);
        damianbluerect = (ImageView) AboutView.findViewById(R.id.damianbluerect);
        rectangle_4 = (View) AboutView.findViewById(R.id.rectangle_4);
        damian_marta_is_an_undergraduate_student_majoring_in_computer_science_at_eastern_new_mexico_university__he_is_interested_in_data_analysis_and_web_and_app_development__he_is_always_eager_to_learn_new_stuff_or_ways_to_do_what_he_already_knows_to_be_more_efficient__los_portales_theatre_app_is_the_first_app_he_has_had_the_opportunity_to_build_from_zero__therefore__he_values_the_great_experience_of_working_on_a_project_like_this_one_ = (TextView) AboutView.findViewById(R.id.damian_marta_is_an_undergraduate_student_majoring_in_computer_science_at_eastern_new_mexico_university__he_is_interested_in_data_analysis_and_web_and_app_development__he_is_always_eager_to_learn_new_stuff_or_ways_to_do_what_he_already_knows_to_be_more_efficient__los_portales_theatre_app_is_the_first_app_he_has_had_the_opportunity_to_build_from_zero__therefore__he_values_the_great_experience_of_working_on_a_project_like_this_one_);
        damian_2 = (ImageView) AboutView.findViewById(R.id.damian_2);
        rectangle_3 = (View) AboutView.findViewById(R.id.rectangle_3);
        the_developement_team = (TextView) AboutView.findViewById(R.id.the_developement_team);
        whiterectangle_ = (View) AboutView.findViewById(R.id.whiterectangle_);
        maplosportales = (TextView) AboutView.findViewById(R.id.maplosportales);
        rectangle_2 = (View) AboutView.findViewById(R.id.rectangle_2);
        directions = (TextView) AboutView.findViewById(R.id.directions);
        _mapicon = (ImageView) AboutView.findViewById(R.id._mapicon);
        rectangle_2_ek1 = (View) AboutView.findViewById(R.id.rectangle_2_ek1);
        grayfoundedrectangle = (View) AboutView.findViewById(R.id.grayfoundedrectangle);
        founded_in_1942_by_bob_and_lounette_cox__a_theatrical_and_comedic_production_business_in_portales__new_mexico__performers_created_slapstick_stand_up__talent_shows__and_musical_comedies__the_portales_players_disbanded_after_world_war_ii_broke_out__but_many_of_its_former_members_regrouped_in_1947_to_create_the_los_portales_theatre_company_ = (TextView) AboutView.findViewById(R.id.founded_in_1942_by_bob_and_lounette_cox__a_theatrical_and_comedic_production_business_in_portales__new_mexico__performers_created_slapstick_stand_up__talent_shows__and_musical_comedies__the_portales_players_disbanded_after_world_war_ii_broke_out__but_many_of_its_former_members_regrouped_in_1947_to_create_the_los_portales_theatre_company_);
        community_theatre_1 = (ImageView) AboutView.findViewById(R.id.community_theatre_1);



        _mapicon.setOnClickListener(new View.OnClickListener() {

            public void onClick(View v) {

                Intent nextScreen = new Intent(requireActivity(), MapViewOfLosPortalesLocation.class);
                startActivity(nextScreen);


            }
        });

        // Inflate the layout for this fragment
        return AboutView;
    }
}