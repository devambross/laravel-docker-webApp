<tr class="fila-socio">
    <td>{{ $dni }}</td>
    <td>{{ $nombre }}</td>
    <td>{{ $edad }}</td>
    <td>{{ $relacion }}</td>
    <td>
        <input
            type="checkbox"
            class="check-socio"
            {{ $checked ? 'checked' : '' }}>
    </td>
</tr>
