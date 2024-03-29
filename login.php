<?php

# Includes the autoloader for libraries installed with composer
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Datastore\DatastoreClient;

session_start();

    //My Google Cloud Platform project ID
    $projectId = 's3656798-cc2020';

    //Sets up database
    $data_store = new DatastoreClient([
        'keyFilePath' => 's3656798-cc2020-6770f1694940.json',
        'projectId' => $projectId
    ]);

    //The kind for the new entity
    $kind = 'User';

    //The name/ID for the new entity
    $name = 'sampleuser1';

    //The Cloud Datastore key for the new entity
    $userKey = $data_store->key($kind, $name);

    //Creates the new entity
    $entity = $data_store->entity($userKey,
        [
                'id' => 's3656798',
                'name' => 'Shahrzad Rafezi',
                'password' => 123456
        ]
    );

    //saves the entity
    //$data_store->upsert($entity);

    $id = $_POST['id'];
    $password = (int)$_POST['password'];

    $query = $data_store->query()->kind('User');

    //defines the query
    $query = $data_store->query()
        ->kind('User')
        ->filter('id', '=', $id)
        ->filter('password', '=', $password);

    //runs the query
    $result = $data_store->runQuery($query);

    if( isset($id) && isset($password) && (empty($id) || empty($password)) ){
        echo "<h3>ID and Password cannot be empty</h3>";
    }

    elseif ( isset($id) && isset($password) ) {

        foreach ($result as $properties => $users) {

            if ( $id == $users['id']  &&  $password == $users['password'] ) {
                $_SESSION['authenticated'] = $users['id'];
                $_SESSION['name'] = $users['name'];
                header('Location: /main');
                die();
            }
        }

        if( $id != $users['id']  &&  $password != $users['password'] ) {
            echo "<h3>Incorrect Password or ID</h3>";
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
</head>

<body>
    <form action="#" method="post">
        <label>User ID</label>
        <input type="string" name="id" placeholder="Enter User ID" />
        <br />
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter Password" />
        <br />
        <input type="submit" value="Login" />
    </form>
</body>

</html>
