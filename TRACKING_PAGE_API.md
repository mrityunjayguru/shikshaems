# 📱 Tracking Page API Documentation

## Endpoint

```
GET /api/trip/my-stop-tracking
```

## Purpose

Yeh API tracking page ke liye hai jo user ko uske specific stop ki information deta hai:
- My stop details
- Stop time (scheduled)
- Driver & Helper info
- Real-time ETA
- Live tracking data

---

## Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| trip_id | integer | Yes | Active trip ID |

**Note:** User's pickup point automatically fetched from `transportation_requests` table based on authenticated user.

---

## Request Example

```bash
GET /api/trip/my-stop-tracking?trip_id=13
```

```dart
// Flutter
final response = await http.get(
  Uri.parse('https://shikshaems.com/api/trip/my-stop-tracking?trip_id=13'),
  headers: {'Authorization': 'Bearer $token'},
);
```

---

## Response Example

```json
{
  "error": false,
  "message": "Tracking page data",
  "data": {
    "trip_id": 13,
    "trip_type": "pickup",
    "trip_status": "inprogress",
    
    "vehicle": {
      "number": "UP62BE9706",
      "model": "Tata Bus"
    },
    
    "driver": {
      "name": "Rajesh Kumar",
      "phone": "9876543210",
      "image": "https://example.com/driver.jpg"
    },
    
    "helper": {
      "name": "Suresh Singh",
      "phone": "9876543211",
      "image": "https://example.com/helper.jpg"
    },
    
    "my_stop": {
      "id": 2,
      "name": "Birdhaulpur",
      "latitude": "25.677000",
      "longitude": "82.240957",
      "scheduled_time": "07:30:00",
      "status": "approaching",
      "distance_km": 0.26,
      "eta_minutes": 1
    },
    
    "live_tracking": {
      "current_location": {
        "latitude": 25.67745,
        "longitude": 82.24352,
        "speed": 17.41,
        "device_time": "2026-02-22 10:19:19",
        "ignition": true,
        "battery": 13.4
      },
      "last_update": "2026-02-22 15:49:16"
    },
    
    "socket": {
      "channel": "trip.13",
      "event": "location.update",
      "pusher_key": "your_pusher_key",
      "pusher_cluster": "ap2"
    }
  }
}
```

---

## Response Fields Explanation

### Trip Info
- `trip_id`: Active trip ID
- `trip_type`: "pickup" or "drop"
- `trip_status`: "inprogress", "completed", etc.

### Vehicle Info
- `number`: Vehicle registration number
- `model`: Vehicle model (optional)

### Driver Info
- `name`: Driver full name
- `phone`: Driver contact number
- `image`: Driver photo URL (optional)

### Helper Info
- `name`: Helper full name
- `phone`: Helper contact number
- `image`: Helper photo URL (optional)
- Note: Can be `null` if no helper assigned

### My Stop Info
- `id`: Pickup point ID
- `name`: Stop name
- `latitude`: Stop latitude
- `longitude`: Stop longitude
- `scheduled_time`: Scheduled pickup/drop time (HH:MM:SS)
- `status`: Stop status
  - `completed`: Bus already passed this stop
  - `current`: Bus is at this stop now
  - `approaching`: Bus is heading to this stop
  - `pending`: Bus hasn't reached yet
- `distance_km`: Distance from bus to this stop (in km)
- `eta_minutes`: Estimated time of arrival (in minutes)
  - `0` if bus is at stop
  - `null` if already completed

### Live Tracking
- `current_location`: Real-time bus location
  - `latitude`: Current latitude
  - `longitude`: Current longitude
  - `speed`: Current speed (km/h)
  - `device_time`: GPS device timestamp
  - `ignition`: Engine on/off
  - `battery`: Device battery level
- `last_update`: Last GPS update timestamp
- Note: Can be `null` if no GPS data available yet

### Socket Info
- `channel`: Pusher channel name to subscribe
- `event`: Event name to listen for
- `pusher_key`: Pusher API key
- `pusher_cluster`: Pusher cluster

---

## Stop Status Values

| Status | Description | ETA | Distance |
|--------|-------------|-----|----------|
| `completed` | Bus already passed | `null` | Historical |
| `current` | Bus is at stop now | `0` | ~0 km |
| `approaching` | Bus heading to stop | Real-time | Real-time |
| `pending` | Bus hasn't reached | Real-time | Real-time |

---

## Error Responses

### Trip Not Found
```json
{
  "error": true,
  "message": "Trip not found or inactive"
}
```

### Pickup Point Not Found
```json
{
  "error": true,
  "message": "No approved transportation request found for this user"
}
```

### Stop Not in Route
```json
{
  "error": true,
  "message": "This stop is not part of the trip route"
}
```

### Validation Error
```json
{
  "error": true,
  "message": "The trip id field is required."
}
```

---

## Flutter Integration Example

```dart
class TrackingPageScreen extends StatefulWidget {
  final int tripId;
  
  @override
  _TrackingPageScreenState createState() => _TrackingPageScreenState();
}

class _TrackingPageScreenState extends State<TrackingPageScreen> {
  Map<String, dynamic>? trackingData;
  late PusherChannelsFlutter pusher;
  
  @override
  void initState() {
    super.initState();
    fetchTrackingData();
    initializePusher();
  }
  
  Future<void> fetchTrackingData() async {
    final response = await http.get(
      Uri.parse('https://shikshaems.com/api/trip/my-stop-tracking?trip_id=${widget.tripId}'),
      headers: {'Authorization': 'Bearer $token'},
    );
    
    if (response.statusCode == 200) {
      setState(() {
        trackingData = jsonDecode(response.body)['data'];
      });
    }
  }
  
  Future<void> initializePusher() async {
    pusher = PusherChannelsFlutter.getInstance();
    
    await pusher.init(
      apiKey: trackingData!['socket']['pusher_key'],
      cluster: trackingData!['socket']['pusher_cluster'],
    );
    
    await pusher.connect();
    
    await pusher.subscribe(
      channelName: trackingData!['socket']['channel'],
      onEvent: (event) {
        if (event.eventName == trackingData!['socket']['event']) {
          handleLiveUpdate(event.data);
        }
      },
    );
  }
  
  void handleLiveUpdate(dynamic data) {
    final liveData = jsonDecode(data);
    
    // Update my stop ETA and distance
    if (liveData['stops_status'] != null) {
      for (var stop in liveData['stops_status']) {
        if (stop['id'] == trackingData!['my_stop']['id']) {
          setState(() {
            trackingData!['my_stop']['status'] = stop['status'];
            trackingData!['my_stop']['distance_km'] = stop['distance_km'];
            
            // Calculate ETA
            if (stop['status'] == 'approaching' || stop['status'] == 'pending') {
              final speed = liveData['current_location']['speed'] ?? 30;
              final avgSpeed = speed > 0 ? speed : 30;
              trackingData!['my_stop']['eta_minutes'] = 
                ((stop['distance_km'] / avgSpeed) * 60).round();
            } else if (stop['status'] == 'current') {
              trackingData!['my_stop']['eta_minutes'] = 0;
            }
          });
          break;
        }
      }
    }
    
    // Update live tracking
    setState(() {
      trackingData!['live_tracking'] = {
        'current_location': liveData['current_location'],
        'last_update': liveData['timestamp'],
      };
    });
  }
  
  @override
  Widget build(BuildContext context) {
    if (trackingData == null) {
      return Center(child: CircularProgressIndicator());
    }
    
    return Scaffold(
      appBar: AppBar(title: Text('Track My Bus')),
      body: Column(
        children: [
          // Driver & Helper Info
          Card(
            child: ListTile(
              leading: CircleAvatar(
                backgroundImage: NetworkImage(trackingData!['driver']['image'] ?? ''),
              ),
              title: Text(trackingData!['driver']['name']),
              subtitle: Text('Driver: ${trackingData!['driver']['phone']}'),
            ),
          ),
          
          // My Stop Info
          Card(
            child: Column(
              children: [
                ListTile(
                  title: Text('My Stop: ${trackingData!['my_stop']['name']}'),
                  subtitle: Text('Scheduled: ${trackingData!['my_stop']['scheduled_time']}'),
                ),
                ListTile(
                  leading: Icon(Icons.access_time),
                  title: Text('ETA: ${trackingData!['my_stop']['eta_minutes']} minutes'),
                  subtitle: Text('Distance: ${trackingData!['my_stop']['distance_km']} km'),
                ),
                Chip(
                  label: Text(trackingData!['my_stop']['status'].toUpperCase()),
                  backgroundColor: getStatusColor(trackingData!['my_stop']['status']),
                ),
              ],
            ),
          ),
          
          // Map (implement Google Maps here)
          Expanded(
            child: GoogleMap(
              // Show bus location and my stop
            ),
          ),
        ],
      ),
    );
  }
  
  Color getStatusColor(String status) {
    switch (status) {
      case 'completed': return Colors.green;
      case 'current': return Colors.orange;
      case 'approaching': return Colors.blue;
      default: return Colors.grey;
    }
  }
}
```

---

## Use Cases

### 1. Initial Page Load
- Call API once to get all static data (driver, helper, scheduled time)
- Get initial ETA and distance
- Get Pusher credentials

### 2. Real-Time Updates
- Subscribe to Pusher channel
- Receive automatic updates every 5-10 seconds
- Update ETA and distance dynamically
- Update stop status

### 3. Refresh
- Call API again if needed
- Or rely on Pusher for real-time data

---

## Notes

1. **ETA Calculation**: 
   - Based on current speed and distance
   - Updates in real-time via Pusher
   - Default speed: 30 km/h if bus is stopped

2. **Stop Status**:
   - Automatically calculated based on bus proximity
   - Updates in real-time

3. **Live Tracking**:
   - Can be `null` if GPS data not available yet
   - Updates every 5-10 seconds via Pusher

4. **Helper**:
   - Can be `null` if no helper assigned to trip

---

**API Ready! 🚀**

Test karne ke liye:
```bash
curl -X GET "https://shikshaems.com/api/trip/my-stop-tracking?trip_id=13&pickup_point_id=2" \
  -H "Authorization: Bearer YOUR_TOKEN"
```
