import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

export default new Echo({
  broadcaster: 'pusher',
  key: 'your-pusher-key',              // from Pusher dashboard
  cluster: 'mt1',                       // adjust to your cluster
  forceTLS: true,
  encrypted: true,
  authEndpoint: 'http://localhost:8000/broadcasting/auth',
  auth: {
    withCredentials: true,             // for Laravel Sanctum
  },
})
