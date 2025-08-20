@foreach($coupons as $index => $coupon)
<tr>
    <td>{{ $index + 1 }}</td>
    <td>{{ $coupon->coupon_code }}</td>
    <td>{{ $coupon->coupon_percent }}%</td>
    <td>{{ $coupon->status }}</td>
    <td>{{ \Carbon\Carbon::parse($coupon->coupon_date)->format('d-m-Y') }}</td>
    <td>
        <a href="{{ route('vendor.coupon.edit', $coupon->id) }}" class="btn btn-success"><i class="fa fa-pencil"></i></a>
        <a href="{{ route('vendor.coupon.delete', $coupon->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger"><i class="fa fa-trash"></i></a>
    </td>
</tr>
@endforeach
