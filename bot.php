<?

$api_token = getenv('API_TOKEN');
$target_ip = getenv('TARGET');

function api_call($api_token, $endpoint, $post_data) {
    $url = 'https://apicyber.space'.$endpoint;
    $post_data['api_auth_token'] = $api_token;

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($post_data),
            'ignore_errors' => true
        ),
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    error_log("Api call ".$endpoint." with POST=".json_encode($post_data)." **RESULT**: ".$result);
    return json_decode($result, true);
}

//Attempt to release the node (only works if we control it)
api_call($api_token, '/ip_control_panel.php?ip='.$target_ip.'&act=release_node', array());

//Attempt to capture the node (only works if Autopwn has finished hacking it)
api_call($api_token, '/ip_hack.php?ip='.$target_ip.'&act=capture_node', array());

//Run Autopwn (only works if we don't control the node already)
api_call($api_token, '/ip_hack.php?ip='.$target_ip.'&act=run_autopwn', array());

?>