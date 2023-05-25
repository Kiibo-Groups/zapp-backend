<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Items</title>
</head>
<body>
<table>
  <thead>
    <tr>
      <td><b>id</b></td>
      <td><b>title</b></td>
      <td><b>description</b></td>
      <td><b>availability</b></td>
      <td><b>condition</b></td>
      <td><b>price</b></td>
      <td><b>link</b></td>
      <td><b>image_link</b></td>
      <td><b>brand</b></td>
      <td><b>last_price</b></td>
      <td><b>category</b></td>
    </tr>
  </thead>
  <tbody> 
    @foreach($data as $row)
    <tr>
      <td>{{ $row['id'] }}</td>
      <td>{{ $row['title'] }}</td>
      <td>{{ $row['description'] }}</td>
      <td>{{ $row['availability'] }}</td>
      <td>{{ $row['condition'] }}</td>
      <td>{{ $row['price'] }}</td>
      <td>{{ $row['link'] }}</td>
      <td>{{ $row['image_link'] }}</td>
      <td>{{ $row['brand'] }}</td>
      <td>{{ $row['last_price'] }}</td> 
      <td>{{ $row['category'] }}</td>
    </tr>
    @endforeach   
  </tbody>
</table> 
</body>
</html>