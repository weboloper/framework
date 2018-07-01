<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
 
            <li class="nav-item">
                <a href="/admin" class="nav-link">
                    <i class="fas fa-tachometer-alt text-light"></i> Dashboard
                </a>
            </li>

            {% for item in postTypes %}
                <li class="nav-item nav-dropdown {{ tab  == item['slug'] ? 'open' : '' }}">
                    <a href="#" class="nav-link nav-dropdown-toggle">
                        <i class="fas fa-{{item['icon']}} text-light"> </i> {{ item['name']}} <i class="fas fa-caret-left"></i>
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                                  {{ link_to('admin/posts?type=' ~ item['slug'],  
                                    'All ' ~ item['name'] , 
                                    'title': item['name'] , 
                                    'class': 'nav-link') }}
                         </li>

                         <li class="nav-item">
                                  {{ link_to('admin/posts/new?type=' ~ item['slug'],  
                                    'New' , 
                                    'title': item['name'] , 
                                    'class': 'nav-link') }}
                         </li>

                    </ul>
                </li>
            {% endfor %}

            <li class="nav-title">Terms</li>

            {% for item in termTypes %}
                <li class="nav-item nav-dropdown {{ tab  == item['taxonomy'] ? 'open' : '' }}">
                    <a href="#" class="nav-link nav-dropdown-toggle">
                        <i class="fas fa-{{item['icon']}} text-light"> </i> {{ item['name']}} <i class="fas fa-caret-left"></i>
                    </a>

                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                                  {{ link_to('admin/terms?taxonomy=' ~ item['taxonomy'],  
                                    'All ' ~ item['name'] , 
                                    'title': item['name'] , 
                                    'class': 'nav-link') }}
                         </li>

                         <li class="nav-item">
                                  {{ link_to('admin/terms/new?taxonomy=' ~ item['taxonomy'],  
                                    'New' , 
                                    'title': item['name'] , 
                                    'class': 'nav-link') }}
                         </li>

                    </ul>
                </li>
            {% endfor %}

            <li class="nav-title">Users</li>
            <li class="nav-item nav-dropdown {{ tab  == 'users' ? 'open' : '' }}">
                <a href="#" class="nav-link nav-dropdown-toggle">
                    <i class="fas fa-user text-light"> </i> Users <i class="fas fa-caret-left"></i>
                </a>

                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                              {{ link_to('admin/users',  
                                'All Users', 
                                'title': 'Users' , 
                                'class': 'nav-link') }}
                     </li>

                     <li class="nav-item">
                              {{ link_to('admin/users/new',  
                                'New' , 
                                'title': 'Users' , 
                                'class': 'nav-link') }}
                     </li>

                </ul>
            </li>
           
        </ul>
    </nav>

    <a href="http://www.projeksen.com" target="_blank">Projeksen</a>
</div>