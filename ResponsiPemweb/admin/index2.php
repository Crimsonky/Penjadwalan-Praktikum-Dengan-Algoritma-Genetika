<?php

// include 'koneksi.php';

require '../function.php';
if(isset($_SESSION["email"])){
}
else{
header("Location: login.php");
}

$query_get_configuration = "SELECT * FROM tb_konfigurasi ";
$result_get_configuration = mysqli_query($conn, $query_get_configuration);
$row_configuration = mysqli_fetch_assoc($result_get_configuration);

$sql = "SELECT kode_mk FROM tb_input_kelas"; // Ganti dengan query yang sesuai
$sql2 = "SELECT kode_ruang FROM tb_ruangan";
$sql3 = "SELECT nim FROM tb_asisten_praktikum";
$sql4 = "SELECT id_jam FROM tb_jam_praktikum";
$sql5 = "SELECT id_hari from tb_hari";
$result = $conn->query($sql);
$result2 = $conn->query($sql2);
$result3 = $conn->query($sql3);
$result4 = $conn->query($sql4);
$result5 = $conn->query($sql5);


$courses = array();
$rooms = array();
$mentor = array();
$timeslots = array();
$day = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $courses[] = $row["kode_mk"];
    }
} else {
    echo "Tidak ada data yang ditemukan";
}

if ($result2->num_rows > 0) {
    while($row = $result2->fetch_assoc()) {
        $rooms[] = $row["kode_ruang"];
    }
} else {
    echo "Tidak ada data yang ditemukan";
}

if ($result3->num_rows > 0) {
    while($row = $result3->fetch_assoc()) {
        $mentor[] = $row["nim"];
    }
} else {
    echo "Tidak ada data yang ditemukan";
}

if ($result4->num_rows > 0) {
    while($row = $result4->fetch_assoc()) {
        $timeslots[] = $row["id_jam"];
    }
} else {
    echo "Tidak ada data yang ditemukan";
}

if ($result4->num_rows > 0) {
    while($row = $result5->fetch_assoc()) {
        $day[] = $row["id_hari"];
    }
} else {
    echo "Tidak ada data yang ditemukan";
}


// Tutup koneksi database
// $conn->close();

// Konfigurasi algoritma genetika
$populationSize = $row_configuration['populationSize'];
$mutationRate = $row_configuration['mutationRate']; // Persentase mutasi
$generations = $row_configuration['generations'];

// Definisi kromosom (jadwal praktikum)
class Schedule {
    public $genes; // Genes berisi informasi tentang mata kuliah, ruangan, dan waktu

    public function __construct($genes = array()) {
        $this->genes = $genes;
    }

    // Inisialisasi kromosom acak
    public static function random($courses, $rooms, $timeslots, $day, $mentor) {
        $genes = array();
        foreach ($courses as $course) {
            $genes[] = array(
                'course' => $course,
                'room' => $rooms[array_rand($rooms)],
                'timeslot' => $timeslots[array_rand($timeslots)],
                'day' => $day[array_rand($day)],
                'mentor' => $mentor[array_rand($mentor)]
            );
        }
        shuffle($genes); // Acak urutan praktikum
        return new Schedule($genes);
    }

    // Mendapatkan fitness score (nilai kecocokan)
    public function fitness() {
        $penalty = 0;
        $scheduleInfo = array();

        foreach ($this->genes as $gene) {
            // 1. Kondisi 1 : Jika dua mata kuliah praktikum mempunyai ruangan dan jam yang sama pada hari yang sama.
            $key = $gene['day'] . '-' . $gene['timeslot'] . '-' . $gene['room'];
            if (isset($scheduleInfo[$key])) {
                $penalty++;
            } else {
                $scheduleInfo[$key] = true;
            }

            // 2. Kondisi 2: Jika ada mata kuliah praktikum pada hari Jumat pukul 12.00.
            if ($gene['day'] == 'H5' && $gene['timeslot'] == 'T03') {
                // Jika ada matakuliah pada hari Jumat pukul 12:00, berikan penalty
                $penalty++;
                break; 
            }

            // 3. Kondisi 3: Jika ada asisten praktikum pada ruangan, slot waktu, dan hari yang sama.
            $assistantKey = $gene['day'] . '-' . $gene['timeslot'] . '-' . $gene['room'] . '-' . $gene['mentor'];
            if (isset($scheduleInfo[$assistantKey])) {
                $penalty++;
            } else {
                $scheduleInfo[$assistantKey] = true;
            }

            // 4. Kondisi 4: Jika seorang asisten praktikum mengajar lebih dari dua kali.
            if (!isset($mentorCounts[$gene['mentor']])) {
                $mentorCounts[$gene['mentor']] = 0;
            }
            $mentorCounts[$gene['mentor']]++;
            if ($mentorCounts[$gene['mentor']] > 2) {
                $penalty++;
            }  
            
            // 5. Kondisi 5:Jika mata kuliah praktikum yang sama muncul lebih dari 4 kali.
            if (!isset($courseCounts[$gene['course']])) {
                $courseCounts[$gene['course']] = 0;
            }
            $courseCounts[$gene['course']]++;
            if ($courseCounts[$gene['course']] > 4) {
                $penalty++;
            }               
        } 
        $fitnessScore = 1 / (1 + $penalty);
        return $fitnessScore;
    }
}

// Algoritma Genetika
class GeneticAlgorithm {
    public $populationSize;
    public $mutationRate;
    public $courses;
    public $rooms;
    public $timeslots;
    public $day;
    public $mentor;

    public function __construct($populationSize, $mutationRate, $courses, $rooms, $timeslots, $day, $mentor) {
        $this->populationSize = $populationSize;
        $this->mutationRate = $mutationRate;
        $this->courses = $courses;
        $this->rooms = $rooms;
        $this->timeslots = $timeslots;
        $this->day = $day;
        $this->mentor = $mentor;
    }

    // Inisialisasi populasi awal
    public function initPopulation() {
        $population = array();
        for ($i = 0; $i < $this->populationSize; $i++) {
            $population[] = Schedule::random($this->courses, $this->rooms, $this->timeslots, $this->day, $this->mentor);
        }
        return $population;
    }

    public function selectParent($population) {
        $totalFitness = array_reduce($population, function ($carry, $schedule) {
            return $carry + $schedule->fitness();
        }, 0);

        $rand = mt_rand(0, $totalFitness);

        $currentFitness = 0;
        foreach ($population as $schedule) {
            $currentFitness += $schedule->fitness();
            if ($currentFitness >= $rand) {
                return $schedule;
            }
        }
    }

    // Melakukan crossover untuk menghasilkan anak
    public function crossover($parent1, $parent2) {
        $genes1 = $parent1->genes;
        $genes2 = $parent2->genes;
        $childGenes = array();
        $splitPoint = rand(1, count($genes1) - 1);
        for ($i = 0; $i < $splitPoint; $i++) {
            $childGenes[] = $genes1[$i];
        }
        for ($i = $splitPoint; $i < count($genes2); $i++) {
            $childGenes[] = $genes2[$i];
        }
        return new Schedule($childGenes);
    }

    // Melakukan mutasi pada anak dengan peluang mutasi tertentu
    public function mutate($child) {
        foreach ($child->genes as &$gene) {
            if (rand(0, 100) < $this->mutationRate) {

                $gene['room'] = $this->rooms[array_rand($this->rooms)];
                $gene['timeslot'] = $this->timeslots[array_rand($this->timeslots)];
                $gene['mentor'] = $this->mentor[array_rand($this->mentor)];
                $gene['day'] = $this->day[array_rand($this->day)];
            }
        }
        return $child;
    }

    // Melakukan evolusi generasi
    public function regenerasi($population) {
        $newPopulation = array();

        // Elitism: Menyimpan beberapa individu terbaik dari populasi sebelumnya
        $eliteCount = 3;
        $sortedPopulation = $population;
        usort($sortedPopulation, function($a, $b) {
            return $b->fitness() - $a->fitness();
        });
        for ($i = 0; $i < $eliteCount; $i++) {
            $newPopulation[] = $sortedPopulation[$i];
        }

        // Melakukan crossover dan mutasi untuk menghasilkan generasi berikutnya
        while (count($newPopulation) < $this->populationSize) {
            $parent1 = $this->selectParent($population);
            $parent2 = $this->selectParent($population);
            $child = $this->crossover($parent1, $parent2);
            $child = $this->mutate($child);
            $newPopulation[] = $child;
        }

        return $newPopulation;
    }

    // Mencari solusi terbaik
    public function findSolution($generations) {
        $bestGeneration = 0; // Variabel untuk menyimpan generasi terbaik
        $population = $this->initPopulation();
        $bestSchedule = $population[0]; // Variabel untuk menyimpan jadwal terbaik
        for ($i = 0; $i < $generations; $i++) {
            $population = $this->regenerasi($population);
            foreach ($population as $schedule) {
                if ($schedule->fitness() > $bestSchedule->fitness()) {
                    $bestSchedule = $schedule;
                    $bestGeneration = $i + 1; // Mengupdate generasi terbaik
                }
            }
        }
        echo "Solusi terbaik ditemukan pada generasi ke-" . ($bestGeneration + 1) . "<br>";

        // Simpan solusi terbaik ke dalam tabel tb_jadwal
        $this->saveBestSchedule($bestSchedule, $bestGeneration);

        return $bestSchedule;
    }

    // Metode untuk menyimpan solusi terbaik ke dalam tabel tb_jadwal
    private function saveBestSchedule($bestSchedule, $bestGeneration) {
        global $conn;

        // Bersihkan tabel tb_jadwal sebelum menyimpan jadwal baru
        $truncateQuery = "TRUNCATE TABLE tb_jadwal";
        $conn->query($truncateQuery);

        // Siapkan query untuk menyimpan jadwal baru
        $insertQuery = "INSERT INTO tb_jadwal (kode_mk, kode_ruang, id_hari, id_jam, nim, best_generation, fitness_score) VALUES ";
        $values = array();

        // Bangun query dengan nilai dari solusi terbaik
        foreach ($bestSchedule->genes as $gene) {
            $values[] = "('" . $gene['course'] . "', '" . $gene['room'] . "', '" . $gene['day'] . "', '" . $gene['timeslot'] . "', '" . $gene['mentor'] . "', '" . $bestGeneration+1 . "', '" . $bestSchedule->fitness() . "')";
        }

        // Gabungkan nilai-nilai menjadi satu query INSERT
        $insertQuery .= implode(",", $values);

        // Eksekusi query INSERT
        $conn->query($insertQuery);
    }

}

// Membuat objek algoritma genetika dan menemukan solusi
$ga = new GeneticAlgorithm($populationSize, $mutationRate, $courses, $rooms, $timeslots, $day, $mentor);
$bestSchedule = $ga->findSolution($generations);
?>
        <table border="1" class="tabelproduk">
            <tr>
                <th>Kelas Matakuliah</th>
                <th>Ruangan</th>
                <th>Hari</th>
                <th>Waktu</th>
                <th>Asisten Praktikum</th>
            </tr>
            <?php
                foreach ($bestSchedule->genes as $gene) {
                    $sql_matkul = "SELECT nama_matakuliah FROM tb_matakuliah WHERE kode_mk = '" . $gene['course'] . "'";
                    // Eksekusi kueri SQL untuk mendapatkan nama matakuliah
                    $result_matkul = mysqli_query($conn, $sql_matkul);
                    if ($result_matkul && mysqli_num_rows($result_matkul) > 0) {
                        $row_matkul = mysqli_fetch_assoc($result_matkul);
                        $nama_matkul = $row_matkul['nama_matakuliah'];
                    } else {
                        $nama_matkul = "Matakuliah tidak ditemukan";
                    }

                    $sql_ruang = "SELECT nama_ruangan FROM tb_ruangan WHERE kode_ruang = '" . $gene['room'] . "'";  
                    $result_ruang = mysqli_query($conn, $sql_ruang);                  
                    if ($result_ruang && mysqli_num_rows($result_ruang) > 0) {
                        $row_ruang = mysqli_fetch_assoc($result_ruang);
                        $nama_ruang = $row_ruang['nama_ruangan'];
                    } else {
                        $nama_ruang = "ruangan tidak ditemukan";
                    }
                    
                    $sql_mentor = "SELECT nama FROM tb_asisten_praktikum WHERE nim = '" . $gene['mentor'] . "'";  
                    $result_mentor = mysqli_query($conn, $sql_mentor);                  
                    if ($result_mentor && mysqli_num_rows($result_mentor) > 0) {
                        $row_mentor = mysqli_fetch_assoc($result_mentor);
                        $nama_mentor = $row_mentor['nama'];
                    } else {
                        $nama_mentor = "praktikan tidak ditemukan"; 
                    }
                    
                    $sql_jam = "SELECT jam FROM tb_jam_praktikum WHERE id_jam = '" . $gene['timeslot'] . "'";  
                    $result_jam = mysqli_query($conn, $sql_jam);                  
                    if ($result_jam && mysqli_num_rows($result_jam) > 0) {
                        $row_jam = mysqli_fetch_assoc($result_jam);
                        $jam = $row_jam['jam'];
                    } else {
                        $jam = "jam tidak ditemukan"; 
                    }

                    $sql_day = "SELECT hari FROM tb_hari WHERE id_hari = '" . $gene['day'] . "'";  
                    $result_day = mysqli_query($conn, $sql_day);                  
                    if ($result_day && mysqli_num_rows($result_day) > 0) {
                        $row_day = mysqli_fetch_assoc($result_day);
                        $day = $row_day['hari'];
                    } else {
                        $day = "hari tidak ditemukan"; 
                    }        

                    echo '<tr class="schedule-item">';
                    echo "<td>" .  $nama_matkul . "</td>";
                    echo "<td>" . $nama_ruang . "</td>";
                    echo "<td>" . $day . "</td>";
                    echo "<td>" . $jam . "</td>";
                    echo "<td>" . $nama_mentor . "</td>";
                    echo '</tr>';
                }
            ?>
        </table>
        <p><strong>Fitness Score:</strong> <?php echo $bestSchedule->fitness(); ?></p>
