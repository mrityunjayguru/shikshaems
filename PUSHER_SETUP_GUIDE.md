# 🚀 Live Bus Tracking - Pusher Setup Guide

## 📋 Overview

Pure socket-based live tracking system using Pusher. No API calls needed for real-time updates!

---

## 🔄 Complete Flow

```
Driver starts trip
    ↓
Traccar sends GPS to Backend
    ↓
Backend (SimpleTripTrackingService) calculates:
    - Current stop
    - Next stop
    - Distance to next stop
    - ETA in minutes
    - Stop status (completed/current/pending)
    ↓
Backend broadcasts via Pusher
    ↓
Parent opens app → Connects to Pusher → Gets real-time updates
```

**No API calls needed!** Pure socket communication.

---

## 🔧 Backend Setup (Already Done ✅)

### 1. Pusher Package Installed
```bash
✅ pusher/pusher-php-server v7.2.7
```

### 2. Environment Configuration
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id_here
PUSHER_APP_KEY=your_app_key_here
PUSHER_APP_SECRET=your_app_secret_here
PUSHER_APP_CLUSTER=ap2
```

### 3. Broadcasting Event
```php
// app/Events/TripLocationUpdate.php
Channel: trip.{trip_id}
Event: location.update
```

### 4. Traccar Listener Running
```bash
✅ php artisan traccar:listen
```

---

## 📱 Flutter Integration

### Step 1: Get Pusher Credentials

**Go to:** https://dashboard.pusher.com/

1. Sign up / Login
2. Create new app: "ShikshaEMS Live Tracking"
3. Select cluster: **Asia Pacific (Mumbai) - ap2**
4. Copy credentials:
   - App ID
   - Key
   - Secret
   - Cluster

### Step 2: Update Backend .env

Replace in `.env` file:
```env
PUSHER_APP_ID=1234567
PUSHER_APP_KEY=abcdef123456
PUSHER_APP_SECRET=xyz789secret
PUSHER_APP_CLUSTER=ap2
```

Then run:
```bash
php artisan config:clear
php artisan cache:clear
```

### Step 3: Restart Traccar Listener

```bash
# Find process
ps aux | grep traccar

# Kill old process
kill <PID>

# Start new
nohup php artisan traccar:listen > storage/logs/traccar-output.log 2>&1 &
```

---

## 📱 Flutter Code

### Install Package

```yaml
# pubspec.yaml
dependencies:
  pusher_channels_flutter: ^2.2.1
  google_maps_flutter: ^2.5.0
```

### Complete Implementation

```dart
import 'package:pusher_channels_flutter/pusher_channels_flutter.dart';
import 'dart:convert';

class LiveTrackingScreen extends StatefulWidget {
  final int tripId;
  
  const LiveTrackingScreen({required this.tripId});
  
  @override
  _LiveTrackingScreenState createState() => _LiveTrackingScreenState();
}

class _LiveTrackingScreenState extends State<LiveTrackingScreen> {
  late PusherChannelsFlutter pusher;
  Map<String, dynamic>? trackingData;
  bool isConnected = false;
  
  @override
  void initState() {
    super.initState();
    initializePusher();
  }
  
  Future<void> initializePusher() async {
    pusher = PusherChannelsFlutter.getInstance();
    
    try {
      await pusher.init(
        apiKey: 'YOUR_PUSHER_KEY',
        cluster: 'ap2',
        onConnectionStateChange: onConnectionStateChange,
        onError: onError,
        onEvent: onEvent,
      );
      
      await pusher.connect();
      
      // Subscribe to trip channel
      await pusher.subscribe(
        channelName: 'trip.${widget.tripId}',
        onEvent: (event) {
          print('Event received: ${event.eventName}');
          if (event.eventName == 'location.update') {
            handleLocationUpdate(event.data);
          }
        },
      );
      
      print('✅ Subscribed to trip.${widget.tripId}');
      
    } catch (e) {
      print('❌ Pusher Error: $e');
    }
  }
  
  void handleLocationUpdate(dynamic data) {
    try {
      final locationData = jsonDecode(data);
      
      setState(() {
        trackingData = locationData;
      });
      
      print('📍 Bus Location: ${locationData['current_location']}');
      print('🚏 Next Stop: ${locationData['next_stop']?['name']}');
      print('⏱️ ETA: ${locationData['eta_minutes']} minutes');
      
      // Update map marker
      if (locationData['current_location'] != null) {
        updateBusMarker(
          locationData['current_location']['latitude'],
          locationData['current_location']['longitude'],
        );
      }
      
    } catch (e) {
      print('Error parsing data: $e');
    }
  }
  
  void updateBusMarker(double lat, double lng) {
    // Update your Google Map marker here
    print('Updating marker: $lat, $lng');
  }
  
  void onConnectionStateChange(dynamic currentState, dynamic previousState) {
    setState(() {
      isConnected = currentState == 'connected';
    });
    print('Connection: $previousState -> $currentState');
  }
  
  void onError(String message, int? code, dynamic e) {
    print('Pusher Error: $message (code: $code)');
  }
  
  void onEvent(PusherEvent event) {
    print('Event: ${event.eventName} on ${event.channelName}');
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Live Tracking - Trip ${widget.tripId}'),
        actions: [
          Icon(
            isConnected ? Icons.wifi : Icons.wifi_off,
            color: isConnected ? Colors.green : Colors.red,
          ),
          SizedBox(width: 16),
        ],
      ),
      body: trackingData == null
          ? Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  CircularProgressIndicator(),
                  SizedBox(height: 16),
                  Text('Waiting for GPS data...'),
                  Text('Connected: ${isConnected ? "Yes" : "No"}'),
                ],
              ),
            )
          : buildTrackingUI(),
    );
  }
  
  Widget buildTrackingUI() {
    final location = trackingData!['current_location'];
    final currentStop = trackingData!['current_stop'];
    final nextStop = trackingData!['next_stop'];
    final eta = trackingData!['eta_minutes'];
    final distance = trackingData!['distance_to_next'];
    
    return Column(
      children: [
        // Map (implement Google Maps here)
        Expanded(
          flex: 2,
          child: Container(
            color: Colors.grey[300],
            child: Center(child: Text('Google Map Here')),
          ),
        ),
        
        // Info Panel
        Expanded(
          child: SingleChildScrollView(
            padding: EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Vehicle Info
                Card(
                  child: ListTile(
                    leading: Icon(Icons.directions_bus, color: Colors.blue),
                    title: Text(trackingData!['vehicle_number'] ?? 'N/A'),
                    subtitle: Text('Speed: ${location['speed']} km/h'),
                  ),
                ),
                
                SizedBox(height: 8),
                
                // Current Stop
                if (currentStop != null)
                  Card(
                    child: ListTile(
                      leading: Icon(Icons.location_on, color: Colors.orange),
                      title: Text('Current Stop'),
                      subtitle: Text(currentStop['name']),
                      trailing: Text('${currentStop['distance_km']} km'),
                    ),
                  ),
                
                SizedBox(height: 8),
                
                // Next Stop
                if (nextStop != null)
                  Card(
                    child: ListTile(
                      leading: Icon(Icons.flag, color: Colors.green),
                      title: Text('Next Stop'),
                      subtitle: Text(nextStop['name']),
                      trailing: Column(
                        mainAxisAlignment: MainAxisAlignment.center,
                        crossAxisAlignment: CrossAxisAlignment.end,
                        children: [
                          Text('$distance km'),
                          Text('ETA: $eta min', 
                            style: TextStyle(fontSize: 12, color: Colors.grey)),
                        ],
                      ),
                    ),
                  ),
                
                SizedBox(height: 16),
                
                // All Stops
                Text('All Stops', style: TextStyle(
                  fontSize: 18, 
                  fontWeight: FontWeight.bold,
                )),
                SizedBox(height: 8),
                
                ...buildStopsList(),
              ],
            ),
          ),
        ),
      ],
    );
  }
  
  List<Widget> buildStopsList() {
    final stops = trackingData!['stops_status'] as List;
    
    return stops.map((stop) {
      Color statusColor;
      IconData statusIcon;
      
      switch (stop['status']) {
        case 'completed':
          statusColor = Colors.green;
          statusIcon = Icons.check_circle;
          break;
        case 'current':
          statusColor = Colors.orange;
          statusIcon = Icons.location_on;
          break;
        case 'approaching':
          statusColor = Colors.blue;
          statusIcon = Icons.near_me;
          break;
        default:
          statusColor = Colors.grey;
          statusIcon = Icons.circle_outlined;
      }
      
      return Card(
        child: ListTile(
          leading: Icon(statusIcon, color: statusColor),
          title: Text(stop['name']),
          subtitle: Text(stop['status'].toUpperCase()),
          trailing: Text('${stop['distance_km']} km'),
        ),
      );
    }).toList();
  }
  
  @override
  void dispose() {
    pusher.unsubscribe(channelName: 'trip.${widget.tripId}');
    pusher.disconnect();
    super.dispose();
  }
}
```

---

## 📊 Real-Time Data Format

Every 5-10 seconds, you'll receive:

```json
{
  "trip_id": 12,
  "vehicle_number": "UP62BE9706",
  "current_location": {
    "latitude": 28.6139,
    "longitude": 77.2090,
    "speed": 45.5,
    "device_time": "2026-02-20 17:30:00",
    "ignition": true,
    "battery": 85
  },
  "current_stop": {
    "id": 5,
    "name": "Main Gate",
    "distance_km": 0.05
  },
  "next_stop": {
    "id": 6,
    "name": "Park Street",
    "latitude": 28.6200,
    "longitude": 77.2150
  },
  "distance_to_next": 2.5,
  "eta_minutes": 8,
  "stops_status": [
    {
      "id": 1,
      "name": "School",
      "latitude": 28.6000,
      "longitude": 77.2000,
      "status": "completed",
      "distance_km": 5.2,
      "order": 1
    },
    {
      "id": 5,
      "name": "Main Gate",
      "status": "current",
      "distance_km": 0.05,
      "order": 2
    },
    {
      "id": 6,
      "name": "Park Street",
      "status": "pending",
      "distance_km": 2.5,
      "order": 3
    }
  ],
  "timestamp": "2026-02-20 17:30:15"
}
```

---

## 🧪 Testing

### Backend Testing

```bash
# Check if Traccar listener is running
ps aux | grep traccar

# Monitor logs
tail -f storage/logs/laravel.log | grep -i "broadcasted"

# You should see:
# 📡 Broadcasted location for trip 12
```

### Flutter Testing

```dart
// Add debug prints
pusher.init(
  onEvent: (event) {
    print('📨 Event: ${event.eventName}');
    print('📦 Data: ${event.data}');
  },
);
```

---

## ⚠️ Important Notes

### 1. Channel Name Format
```dart
// ✅ Correct
channelName: 'trip.123'

// ❌ Wrong
channelName: 'trip-123'
channelName: 'Trip.123'
```

### 2. Event Name
```dart
// ✅ Correct
event.eventName == 'location.update'

// ❌ Wrong
event.eventName == 'location-update'
```

### 3. Trip Must Be Active
- Trip status = 'inprogress'
- Trip tracking = 1
- GPS device connected to Traccar

### 4. No API Calls Needed
- Just connect to Pusher
- Subscribe to channel
- Receive real-time updates
- That's it!

---

## 🐛 Troubleshooting

### Not Receiving Updates?

**Check:**
1. Pusher credentials correct in `.env`?
2. `BROADCAST_DRIVER=pusher` in `.env`?
3. Config cleared? (`php artisan config:clear`)
4. Traccar listener running?
5. Trip is active (status = inprogress)?
6. Channel name format: `trip.123`
7. Event name: `location.update`

**Debug:**
```bash
# Backend logs
tail -f storage/logs/laravel.log

# Should see:
# 📡 Broadcasted location for trip 12
```

### Connection Failed?

**Check:**
1. Internet connection
2. Pusher cluster = `ap2`
3. API key valid
4. No firewall blocking

---

## 💰 Pusher Pricing

### Free Tier
- 200,000 messages/day
- 100 concurrent connections
- Perfect for testing & small deployments

### Paid Plans
- $49/month: 1M messages/day
- $99/month: 5M messages/day

**For your use case:** Free tier should be enough initially!

---

## ✅ Checklist

### Backend (Done ✅)
- [x] Pusher package installed
- [x] Broadcasting configured
- [x] TripLocationUpdate event created
- [x] SimpleTripTrackingService implemented
- [x] Traccar listener running

### Flutter Developer Tasks
- [ ] Get Pusher credentials
- [ ] Install pusher_channels_flutter package
- [ ] Implement connection code
- [ ] Subscribe to trip channel
- [ ] Handle location updates
- [ ] Update map UI
- [ ] Test with real GPS data

---

## 🎯 Success Criteria

Your implementation works when:
- ✅ Parent opens app
- ✅ Connects to Pusher automatically
- ✅ Subscribes to trip channel
- ✅ Receives location updates every 5-10 seconds
- ✅ Map marker moves in real-time
- ✅ Stop status updates automatically
- ✅ ETA displays and updates
- ✅ No API calls needed!

---

**Backend Ready! 🚀**

Just get Pusher credentials and start Flutter integration!

---

**Date:** February 22, 2026
**Version:** 1.0
