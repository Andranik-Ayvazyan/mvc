<?php

 return  [
             [

                'method' => 'GET',
                'url' => '/photo',
                'action' => 'PhotoController@view'

             ],
             [

                 'method' => 'GET',
                 'url' => '/photo/welcome',
                 'action' => 'PhotoController@welcome'

             ],
             [
        
                 'method' => 'GET',
                 'url' => '/photo/{name}/user/{param}',
                 'action' => 'PhotoController@user'
        
             ],
             [

                'method' => 'POST',
                'url' => '/photo/create',
                'action' => 'PhotoController@create'

             ],
             [

                 'method' => 'DELETE',
                 'url' => '/photo/delete/{id}',
                 'action' => 'PhotoController@delete'

             ],
             [

                 'method' => 'PUT',
                 'url' => '/photo/edit',
                 'action' => 'PhotoController@edit'

             ]

          ];