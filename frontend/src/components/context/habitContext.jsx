import {createContext, useState, useEffect, useContext} from "react";
import UserContext from "./userContext";

const HabitsContext = createContext();

export const HabitsProvider = ({children}) => {
    const [isLoading, setIsLoading] = useState(true);
    const [habits, setHabits] = useState([]);
    const [habitEdit, setHabitEdit] = useState({habit: {}, edit: false});

    const {user} = useContext(UserContext);

    useEffect(() => {
        fetchHabits();
    }, [])

    // Fetch habits
    const fetchHabits = async () => {
        const response = await fetch('http://localhost:8000/api/habits', {
            credentials: 'include',
        });
        const data = await response.json();

        setHabits(data["hydra:member"]);
        setIsLoading(false);
    }

    // Add habit
    const addHabit = async (newHabit) => {
        const response = await fetch('http://localhost:8000/api/habits', {
            method: 'POST',
            headers: {
                'content-type': 'application/json',
            },
            credentials: 'include',
            body: JSON.stringify(newHabit),
        });

        const data = await response.json();

        if(data['@type'] === "hydra:Error") {
            window.alert(data["hydra:description"]);
        } else {
            setHabits([data, ...habits]);
        }
    }

    // Delete habit
    const deleteHabit = async (id) => {
        if (window.confirm('Are you sure you want to remove this item?')) {
            await fetch(`http://localhost:8000/api/habits/${id}`, { method: 'DELETE', credentials: 'include' });

            setHabits(habits.filter((habit) => habit.id !== id ))
        }
    }

    // Update habit
    const updateHabit = async (id, updHabit) => {
        const response = await fetch(`http://localhost:8000/api/habits/${id}`, {
            method: "PUT",
            headers: {
                'content-type': 'application/ld+json'
            },
            credentials: 'include',
            body: JSON.stringify(updHabit),
        });

        const data = await response.json();

        setHabits(habits.map((habit) => habit.id === id ? {...habit, ...data} : habit))
    }

    // Set habit to be updated
    const editHabit = (habit) => {
        setHabitEdit({habit, edit: true})
    }

    return <HabitsContext.Provider value={{
        habits,
        habitEdit,
        isLoading,
        deleteHabit,
        addHabit,
        editHabit,
        updateHabit,
    }}>{children}</HabitsContext.Provider>
};

export default HabitsContext;