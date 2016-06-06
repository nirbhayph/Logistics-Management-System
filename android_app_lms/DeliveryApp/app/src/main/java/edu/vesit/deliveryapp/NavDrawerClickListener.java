package edu.vesit.deliveryapp;

import android.content.Context;
import android.support.v4.app.Fragment;
import android.support.v4.widget.DrawerLayout;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;

public class NavDrawerClickListener implements ListView.OnItemClickListener
{
    private Context context;
    private ListView mDrawerList;
    private DrawerLayout mDrawerLayout;
    private MainActivity containerActivity;

    public NavDrawerClickListener(Context context, ListView drawerList, DrawerLayout drawerLayout)
    {
        this.context = context;
        containerActivity = (MainActivity) context;
        mDrawerList = drawerList;
        mDrawerLayout = drawerLayout;
    }

    @Override
    public void onItemClick(AdapterView<?> parent, View view, int position, long id)
    {
        displayView(position);
    }

    public void displayView(int position)
    {
        Fragment fragment = null;
        switch (position)
        {
            case 0 : fragment = new StatusFragment();
                     break;
            default:
                break;
        }

        if (fragment != null)
        {
            /*FragmentManager fragmentManager = containerActivity.getSupportFragmentManager();
            fragmentManager.beginTransaction().replace(R.id.main_content_frame, fragment).commit();
            mDrawerList.setItemChecked(position, true);
            mDrawerList.setSelection(position);
            containerActivity.setTitle(containerActivity.navMenuTitles[position]);
            mDrawerLayout.closeDrawer(mDrawerList);*/
        }
        else
        {
            Log.e("MainActivity :  ", "Error in creating fragment");
        }
    }
}