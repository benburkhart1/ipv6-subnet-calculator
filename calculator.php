<?php
	if (isset($_REQUEST['address']))
	{
		$address = $_REQUEST['address'];
		$len     = $_REQUEST['prefixlen'];

		require_once 'IPV6SubnetCalculator.php';

		$calc = new IPV6SubnetCalculator();
		
		if ($calc->testValidAddress($address))
		{
			$rangedata = $calc->getAddressRange($address, $len);

			$ret = array(
				"Abbreviated Address: " . $calc->abbreviateAddress($address) . "\n",
				"Unabbreviated Address: " . $calc->unabbreviateAddress($address) . "\n",
				"Prefix Length: " . $_REQUEST['prefixlen'] . "\n",
				"Number of IPs: " . $calc->getInterfaceCount($len) . "\n",
				"Start IP: " . $rangedata['start_address'] . "\n",
				"End IP: " . $rangedata['end_address'] . "\n",
				"Prefix Address: " . $rangedata['prefix_address'] . "\n",
			);
		} else {
			$ret = array('That is not a valid IPv6 Address');
		}

		die(json_encode($ret));

	}
?>
<html>
	<head>
		<title>IPv6 Subnet Calculator</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<style>
			.calculator {
				width: 650px;
				height: 550px;
				background-color: #cdcdcd;
			}

			.calculator .header {
				background-color: lightsteelblue;
			}

			.calculator .inputs {
				background-color: #ccc;
			}
			
			.ipv6-address {
				width: 345px;
			}
			
			.results {
				height: 400px;
				background-color: white;
				border: 1px dotted #000;
			}
		</style>
		<script type='text/javascript'>
			$(document).ready(function() {
				// Set a default value
				$('.prefix-length').val('64');

				$('.check').click(function() {
					$.ajax({
						url: '<?php echo $_SERVER['SCRIPT_NAME']; ?>',
						data: {
							address: $('.ipv6-address').val(),
							prefixlen: $('.prefix-length').val()
						},
						dataType: 'json',
						success: function(data) {
							$('.results-text').text('');
							$.each(data, function(i, d) {
								$('.results-text').append(d);
							});
						}
					});	
				});
				
			});
		</script>
	</head>
	<body>
		<div class='calculator'>
			<div class='header'>IPv6 Subnet Calculator</div>
			<div class='inputs'>
				<h4>Enter an IPv6 Address and prefix length<h4>
				<input type='text' name='address' class='ipv6-address'>
				<select name='prefix' class='prefix-length'>
					<?php 
						for ($i=8; $i <= 128; $i++)
						{
							echo "<option value='{$i}'>{$i}</option>";
						}
					?>
				</select>
				<button class='check'>Check</button>
			</div>
			<div class='results'>
				<pre class='results-text'></pre>
			</div>
		</div>
	</body>
</html>
