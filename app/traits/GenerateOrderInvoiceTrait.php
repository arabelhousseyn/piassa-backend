<?php
namespace App\traits;

use App\Models\UserOrder;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use PDF;
use Konekt\PdfInvoice\InvoicePrinter;


trait GenerateOrderInvoiceTrait
{
    public function generate($user_order_id)
    {
        $user_order = UserOrder::with('items.item.request.request','user.profile')->find($user_order_id);


//        $pdf = PDF::loadView('invoice', ['user_order' => $user_order]);
//
//        // download PDF file with download method
//        return $pdf->save('../storage/app/public/test.pdf')->stream('pdf_file.pdf');
        $piassa = new Party([
            'name'          => 'Youssef boudjema',
            'phone'         => '345345',
            'address'       => '3737 Sugar Camp Road'
        ]);

        $client = new Party([
            'name'          => $user_order->user->profile->full_name,
            'code'          => $user_order->ref,
            'custom_fields' => [
                'order number' => '> .' . $user_order->id . '. <',
            ],
        ]);

        $items = [];

        foreach ($user_order->items as $item) {

            $items[] = (new InvoiceItem())->title($item->item->mark)->pricePerUnit(strval($item->item->price))
            ->quantity(strval($item->item->request->request->qt));
        }

        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make('Facture')
            ->series('C')
//            ->status(__('invoices::invoice.paid'))
            ->sequence(intval(explode('#',$user_order->ref)[1]))
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($piassa)
            ->buyer($client)
            ->dateFormat('m/d/Y')
            ->payUntilDays(0)
            ->currencySymbol('dzd')
            ->currencyCode('DZD')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($user_order->user->profile->full_name . ' Youssef boudjema')
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('vendor/invoices/sample-logo.png'))
            // You can additionally save generated invoice to configured disk
            ->save('invoice');

         $link = $invoice->filename;

        // And return invoice itself to browser or have a different view
        return 'storage/app/public/invoices/'.$link;
    }
}
