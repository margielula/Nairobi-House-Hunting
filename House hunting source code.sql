USE house_hunting;

INSERT INTO users (name, email, password, role)
VALUES 
('John Agent', 'agent@gmail.com', '1234', 'agent'),
('Mary Seeker', 'seeker@gmail.com', '1234', 'seeker');

INSERT INTO houses (agent_id, house_type, price, location, description)
VALUES 
(1, 'Bedsitter', 8000, 'Kasarani', 'Spacious bedsitter near main road'),
(1, 'One Bedroom', 15000, 'Langata', 'Modern apartment with parking');