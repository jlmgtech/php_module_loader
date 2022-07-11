<?php

class ExampleBilling {

    // static function that POSTs to the Examplebilling.net api
    public static function post(string $endpoint, string $data) {
        $url = "https://examplebilling.net/api/v1/$endpoint";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        try {
            return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function get(string $endpoint) {
        $url = "https://examplebilling.net/api/v1/$endpoint";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        try {
            return json_decode(
                $result,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (Exception $e) {
            return false;
        }
    }

    public function next_bill_date() {
        // curl examplebilling.net to get the next bill date
        // return the date in the format YYYY-MM-DD
        // if the date is in the past, return an empty string
        // if the date is in the future, return the date
        // if the date is invalid, return an empty string

        $result = self::get("next_bill_date");
        try {
            $date = $result["next_bill_date"];
            $date = new DateTime($date);
            $date = $date->format("Y-m-d");
            if (strtotime($date) < time()) {
                return "";
            } else {
                return $date;
            }
        } catch (Exception $e) {
            return "";
        }
    }

    public function add_tag(string $cid, string $tid): string {
        $url = "https://examplebilling.net/api/add_tag";
        $data = [
            "cid" => $cid,
            "tid" => $tid,
        ];
        return self::post($url, $data);
    }

    public function get_config_html() {
        // return an html string to be displayed in the admin area
        return "
            <div>
                <h1>examplebilling</h1>
                <div>configure example Billing Settings</div>
                <form>
                    <div>
                        <label>API Key</label>
                        <input type='text' name='api_key' value=''>
                    </div>
                    <div>
                        <input type='submit' value='Submit'>
                    </div>
                </form>
                <section>
                    <h2>Recent Invoices</h2>
                    <ul>
                        <li>Invoice 1</li>
                        <li>Invoice 2</li>
                        <li>Invoice 3</li>
                    </ul>
                </section>
                <section>
                    <h2>Recent Payments</h2>
                    <ul>
                        <li>Payment 1</li>
                        <li>Payment 2</li>
                        <li>Payment 3</li>
                    </ul>

                </section>
                <section>
                    <h2>Recent Credit Notes</h2>
                    <ul>
                        <li>Credit Note 1</li>
                        <li>Credit Note 2</li>
                        <li>Credit Note 3</li>
                    </ul>
                </section>
                <section>
                    <h2>Recent Debits</h2>
                    <ul>
                        <li>Debit 1</li>
                        <li>Debit 2</li>
                        <li>Debit 3</li>
                    </ul>
                </section>
                <section>
                    <h2>Recent Refunds</h2>
                    <ul>
                        <li>Refund 1</li>
                        <li>Refund 2</li>
                        <li>Refund 3</li>
                    </ul>
                </section>
                <section>
                    <h2>Recent Transactions</h2>
                    <ul>
                        <li>Transaction 1</li>
                        <li>Transaction 2</li>
                        <li>Transaction 3</li>
                    </ul>
                </section>
                <section>
                    <h2>Most Common Invoice Items</h2>
                    <ul>
                        <li>Invoice Item 1</li>
                        <li>Invoice Item 2</li>
                        <li>Invoice Item 3</li>
                    </ul>
                </section>

            </div>
        ";
    }
};
