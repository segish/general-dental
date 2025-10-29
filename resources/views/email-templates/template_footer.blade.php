<div style="margin-top: 100px;">
    <hr>
  </div>

  <div style="display: table; width: 100%; background-color: rgb(233, 227, 227)">
      <div style="display: table-row;">
          <div style="display: table-cell; padding: 10px 20px;">
              <h3 style="color: #1a82e2;">Contact Information:</h3>
              <p>Email: {{ $businessSettings['email'] }}</p>
              <p>Phone: {{ $businessSettings['phone'] }}</p>
          </div>
          <div style="display: table-cell; padding: 10px 20px;">
              <h3 style="color: #1a82e2;">Connect with us:</h3>
              <p>
                @foreach($socialMedias as $socialMedia)
                  <a href=" {{ $socialMedia->link }}" style="color: #1a82e2;" target="_blank">{{ $socialMedia->name }}</a>
                  |
                  @endforeach
              </p>
          </div>
          <div style="display: table-cell; padding: 10px 20px;">
              <h3 style="color: #1a82e2;">Visit us:</h3>
              <p>{{ $businessSettings['shop_name'] }}</p>
              <p>{{ $businessSettings['address'] }}</p>
          </div>
      </div>
  </div>