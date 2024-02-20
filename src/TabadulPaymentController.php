<?php
namespace Jood\tabadul;
use http\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;




class TabadulPaymentController
{
    /**
     * The URL of the payment API.
     *
     * @var string
     */
    private string $apiUrl = 'https://epg.tabadul.iq/epg/rest/register.do';

    /**
     * HTTP Client instance.
     *
     *
     */
    private Client $client;

    /**
     * Payment credentials.
     *
     * @var string $userName
     */
    private string $userName = 'your_api_username';

    /** @var string $password */
    private string $password = 'your_api_password';

    /**
     * @var string $orderNumber
     * Your own order ID that initialed in your DB
     *
     */
    private string $orderNumber = 'your_order_number';

    /** @var string $amount */
    private string $amount = 'your_order_amount'; //IQD

    /** @var string $currency */
    private string $currency = 'your_currency_code'; // Choose '368' for IQD


    /**
     * The URL, which will redirect the user upon completion
     * of the payment, may either succeed or fail.
     * @var  string $returnUrl
     */
    private string $returnUrl = 'your_return_url';

    /**
     *
     * The client ID is stored in your e-commerce payment system.
     * @var string $clientId
     *
     */
    private string $clientId = 'your_client_id';


    /** @var string $description */
    private string $description = 'your_order_description'; // Test value: 'Test desc'


    /**
     * @var string $language
     * Choose (AR) for arabic language
     */
    private string $language = 'your_language_code';

    /**
     * PaymentController constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Process the payment request.
     *
     * @param Request $request
     */


    /**
     * Process the payment request.
     *
     * @param Request $request The HTTP request instance.
     *
     * @return void
     *
     */
    public function processPayment(Request $request): void
    {
        $paymentParams = [
            'userName' => $this->userName,
            'password' => $this->password,
            'orderNumber' => $this->orderNumber,
            'amount' => $this->amount,
            'currancy' => $this->currency,
            'returnUrl' => $this->returnUrl,
            'clientId' => $this->clientId,
            'description' => $this->description,
            'language' => $this->language,
        ];

        $response = $this->client->post($this->apiUrl, [
            'form_params' => $paymentParams,
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
        ]);

        $decodedResponse = json_decode($response->getBody()->getContents(), true);

        if ($this->isValidResponse($decodedResponse)) {
            if ($decodedResponse['errorCode'] === 0) {
                $this->handleSuccess($decodedResponse);
            } elseif ($decodedResponse['errorCode'] === 1) {
                $this->handleDuplicateOrder($decodedResponse);
            } else {
                $this->handleFailure();
            }
        } else {
            $this->handleFailure();
        }
    }

    /**
     * Check if the API response is valid.
     *
     * @param array|null $decodedResponse The decoded API response.
     *
     * @return bool
     */
    private function isValidResponse($decodedResponse)
    {
        return $decodedResponse && isset($decodedResponse['errorCode']);
    }

    /**
     * Handle the success scenario.
     *
     * @param array $response The API response.
     *
     *
     */
    private function handleSuccess($response)
    {

        // Store orderId in the database
        $orderId = $response['orderId'];
        $this->storeOrderIdInDatabase($orderId);


        // Redirect the user to the provided URL
        return $response['formUrl'];
    }

    /**
     * Handle the scenario where a duplicate orderId is detected.
     *
     * @param array $response The API response.
     *
     * @return void
     */
    private function handleDuplicateOrder($response): void
    {


        // Perform additional handling logic if needed, such as generating a new orderId
        // You may want to retry the payment with a new orderId or inform the user accordingly
        // For demonstration purposes, let's just handle it as a failure
        $this->handleFailure();
    }

    /**
     * Store orderId in the database.
     *
     * @param string $orderId The orderId to be stored.
     *
     * @return void
     */
    private function storeOrderIdInDatabase($orderId): void
    {

//        $storeOrder = new Order();
//        $storeOrder->order_id = $orderId;
//        $storeOrder->client_id = $this->clientId;
//        $storeOrder->save();

    }

    /**
     * Handle the failure scenario.
     *
     * @return void
     */
    private function handleFailure(): void
    {

        //echo "Payment failed\n";
    }


    /**
     * Check the payment status by order ID.
     *
     * @param string $orderId The orderId to check the status.
     *
     * @return void
     */
    public function checkPaymentStatus(string $orderId): void
    {
        $statusParams = [
            'userName' => $this->userName,
            'password' => $this->password,
            'orderId' => $orderId,
        ];

        $response = $this->client->post('https://epg.tabadul.iq/epg/rest/getOrderStatus.do', [
            'form_params' => $statusParams,
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
        ]);

        $decodedResponse = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

        $this->handlePaymentStatusResponse($decodedResponse);
    }


    /**
     * Handle the payment status response.
     *
     * @param array $response The API response for payment status.
     *
     * @return void
     */
    private function handlePaymentStatusResponse($response): void
    {
        if ($this->isValidResponse($response)) {
            if ($response['ErrorCode'] === '0') {
                // Payment status is successful
                $this->handleSuccessfulPaymentStatus($response);
            } else {
                // Payment status is declined or has an error
                $this->handleFailedPaymentStatus($response);
            }
        } else {
            echo "Invalid response received while checking payment status\n";
        }
    }

    /**
     * Handle the case when the payment status is successful.
     *
     * @param array $response The successful API response for payment status.
     *
     * @return mixed
     */
    private function handleSuccessfulPaymentStatus($response)
    {
        $decodedResponse = json_decode($response, "", 512, JSON_THROW_ON_ERROR);

        if ($response['OrderStatus'] === 2) {

            return $decodedResponse;
        }

        echo "Invalid OrderStatus for successful payment\n";
    }

    /**
     * Handle the case when the payment status is declined or has an error.
     *
     * @param array $response The API response for payment status with an error.
     *
     * @return mixed
     *
     *
     */
    private function handleFailedPaymentStatus($response)
    {
         return  json_decode($response, "", 512, JSON_THROW_ON_ERROR);
    }
}
