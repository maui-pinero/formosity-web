@extends('dpanel.layouts.app')

@section('title', 'Order Details')

@push('scripts')
    <script>

        const updateStatus = (e, id) => {
            window.location.href = `${window.location.origin}/dpanel/order/status/${id}/${e.value}`;
        }

    </script>
@endpush

@section('body_content')
    <div class="grid grid-cols-1 md:grid-cols-3 rounded-l-md pl-2 mb-3">
        <p>Order ID: <span class="font-medium">#{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</span></p>
        <p>Order Status: <span class="font-medium"><select onchange="updateStatus(this, '{{ $order->id }}')"
                class="bg-white border rounded focus:outline-none">
                <option value="PENDING" @selected($order -> status == 'PENDING')>PENDING</option>
                <option value="PAID OUT" @selected($order -> status == 'PAID OUT')>PAID OUT</option>
                <option value="DISPATCHED" @selected($order -> status == 'DISPATCHED')>DISPATCHED</option>
                <option value="IN TRANSIT" @selected($order -> status == 'IN TRANSIT')>IN TRANSIT</option>
                <option value="DELIVERED" @selected($order -> status == 'DELIVERED')>DELIVERED</option>
            </select></span></p>
        <p>Payment Status: <span class="font-medium">{{ $order->payment_status }}</span></p>
        <p>Name: <span class="font-medium">{{ $order->items[0]->product->title }}</span></p>
        <p>Discount: <span class="font-medium">₱{{ $order->discount_amount }}</span></p>
        <p>Payable: <span class="font-medium">₱{{ $order->total_amount - $order->discount_amount }}</span></p>
    </div>

    <div class="w-full flex flex-col">
        <div class="overflow-x-auto">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden border-b border-gray-600 rounded-md">
                    <table class="min-w-full divide-y divide-gray-600">
                        <thead class="bg-gray-800">
                            <tr>
                                <th scope="col"
                                    class="pl-3 py-3 text-left w-12 text-xs font-medium text-gray-200 tracking-wider">
                                    #
                                </th>

                                <th scope="col"
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Qty
                                </th>

                                <th scope="col"
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Price
                                </th>

                                <th scope="col"
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    MRP
                                </th>

                            </tr>
                        </thead>

                        <tbody class="bg-gray-700 divide-y divide-gray-600">
                            @foreach ($order as $item)
                                <tr>
                                    <td class="pl-3 py-1">
                                        <div class="text-sm text-gray-200">
                                            {{ $loop->iteration }}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">{{ $item->qty }}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">₱{{ $item->selling_price }}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">₱{{ $item->mrp }}</div>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

@endsection
