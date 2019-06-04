<?php 
return [

		//应用ID,您的APPID。
		'app_id' => "2016092700609180",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEA1fclE1i+v+508uv9O8qvsf6qdNdILlfuthjvazaLwsLhxbOcGop0L3L+6etlMk9+Npz+sAgazITY2pNmkETjT8923g8gFAEDw8sls2EnUZCqcE6QfFBZfJ4V0XyzQqptO12l/jxvih1SzxzOP2KjomzE/tiSMlk2RWtbRqXuqOG2wAOQp+XuO5Js3KDCEA3JcMH0A7ly7466T+zv8mxq44LAu2011y6jWiiwvy3vVFlUir7YwmUJ/I3MN7QRw7LwXonCt6WgqBn8Yws4XOrb7Qbvx7kYmXERu1Q1MdKWAL/h8f84Ns0xw59xcN53uaFWgz153up2Ca6XaLVzeDKGuQIDAQABAoIBAHehAOe6PJxPFLAlZaaigm1Ad8lv/Hl8zIiflG0bSDUShOzmqSbltmQ5JXFgHWptq6GZUYTWYzVqKVy8ASI0getDkbBCQKsujg5QbLnIXhVqjwDPzFTVD9NvB7/ius9RPlVs3LwyyiIslvoZnu8tlVPhAJuENulTm9ZgWd48NB3TX2zgtqc6wegOhEOPyyKXb7b7cbXo864hVCuXPaAPZuAlyvRORZS+TK9pqhtVH3G5pX1vHaqdVA2IdmCyQ+KR6qrUzaLaW2Eql383TqmF9i4z11SBL0O11mpenxSkYISUakczhLBw9hjCbX8MkXLWm+suiznindHcd+oZX9aAdWkCgYEA8JOVc4rbkGWUoMVt8XU5LIGMECmAF8vh7UarTeY1G2RQInm0xSp7Akr1AsgPAzJdpM56vZA3hY0kZX/sp2iCc9G+V/kQFGyM3Y4DN+tNTmbKE3KojAreUoOpGUwI8LatNGiNV7ILkk1Og2eFIa8d+9aL7gUHxO/vI/ngSmVhl0cCgYEA467PwrVSoI0syUujjvTwSp3IEjScaepPAwWEBj1BFcqXcJ6+j6VPjukRJ9E0DuRI2d7b6mPb5WqA6yHblqUYG+EY/fPrVzG+DKP+h2O8YSGBhwZEYGx/0AaLwd/d7SjzSdeKo1S4L/WUMbm+f1XlsO59RTNQZWtVm1JuMY5V8f8CgYAq2Y6gEtaCxKURcbjgbgmt9LEW291135mxIoo2iM9ivp5LVbQt/iAEy4cSZMHPxvPI/AH+q6ZIAli+P2fOdZ3utSgbEUCc8qywda/7EM0nLsXFawG8V987gTnoSJO7FD6LfCfYu9y1xVE7tH+Q/Vjw10j7LygwGrBUjxg1CBO21wKBgBpwIBvh5jhSbFLrf5CIDKb9b0/93/PEi2w0ZcI7tqULI06mgEY50eUsVxuihUL9ayAxaqPq+IvJNDMZeWAix4vlNXu3qSo00naxTEr4X9V/iITfS21O7ACB4hSfJCv3x1hZuPWCTujywUSM5vvuQ3+qEc3JSZqdPhGNHtKOTpiNAoGBANDC1sSMA/IQnkNe6apkZUGPFwMrapuDJsOj0psAUCotMyVqm4LbeVwqShsDF6agb8HF3GVCdjidnUK66LkN5X8kmVbpYODNSIgev5oIHsqvQhqQKssOUUm8FEFs1Ne0U6DxExessDxM4ezLg//Zdq8gKXWyUXcSZ3+3UrfQLAWc",
		
		//异步通知地址
		'notify_url' => "http://39.96.25.172/myshop/public/notify",
		
		//同步跳转
		'return_url' => "http://www.1810.com/index/Order/paySuccess",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1fclE1i+v+508uv9O8qvsf6qdNdILlfuthjvazaLwsLhxbOcGop0L3L+6etlMk9+Npz+sAgazITY2pNmkETjT8923g8gFAEDw8sls2EnUZCqcE6QfFBZfJ4V0XyzQqptO12l/jxvih1SzxzOP2KjomzE/tiSMlk2RWtbRqXuqOG2wAOQp+XuO5Js3KDCEA3JcMH0A7ly7466T+zv8mxq44LAu2011y6jWiiwvy3vVFlUir7YwmUJ/I3MN7QRw7LwXonCt6WgqBn8Yws4XOrb7Qbvx7kYmXERu1Q1MdKWAL/h8f84Ns0xw59xcN53uaFWgz153up2Ca6XaLVzeDKGuQIDAQAB",

];



 ?>