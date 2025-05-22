import React from 'react'

const Dashboard = async () => {
    

    return (
        <div>
            <header style={{ display: 'flex', justifyContent: 'flex-end', padding: '1rem', borderBottom: '1px solid #eee' }}>
            </header>
            <main className='flex flex-col items-center justify-center p-8'>
                <h1 className='text-2xl font-semibold mb-4'>Dashboard</h1>
                <p>Welcome to your dashboard!</p>
                {/* You can add more dashboard specific content here */}
            </main>
        </div>
    )
}

export default Dashboard;