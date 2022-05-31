import {HabitsProvider} from "./context/habitContext";
import HabitList from "./habitList";
import HabitStats from "./habitStats";
import HabitForm from "./habitForm";

function Habits() {
    return (
        <HabitsProvider>
            <div>
                <h1>Habits</h1>
                <HabitForm />
                <HabitStats />
                <HabitList />
            </div>
        </HabitsProvider>
    );
}

export default Habits;