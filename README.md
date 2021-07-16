# moviez-backend
A microservice responsible for receiving registered patron/moviegoer, adding genres, list of movies, and finding interested subscribers  to a movie during SMS broadcasting

5 endpoints
  1) http://localhost/moviez/index.php/Genres
  
    name - CRUD
  
  2) http://localhost/moviez/index.php/Registration
  
    name, dob, phone, email, genres - (Push only)
  
  3) http://localhost/moviez/index.php/Movies
  
    date, time, name, starring, genres, rating, description - CRUD
    
    (token involved)
    
  4) http://localhost/moviez/index.php/Broadcast
    
    movie (push only)
    
    (token involved)
