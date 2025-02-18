@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <!-- @if (trim($slot) === 'Laravel') -->
            <!-- <img :src="asset('logo.svg')" class="logo" alt="Organick Logo"> -->
            <!-- @else -->
            <img src="{{asset('logo.svg')}}" class="logo" alt="Organick Logo">
            {{ $slot }}
            <!-- @endif -->
        </a>
    </td>
</tr>