<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<div class="table-responsive">
    <table class="table table-bordered mb-3">
        <thead>
            <tr class="bg-thead">
                <th>No</th>
                <th>Pertanyaan</th>
                <th class="text-center" width="33">Tidak Pernah</th>
                <th class="text-center" width="33">Jarang</th>
                <th class="text-center" width="33">Sering</th>
                <th class="text-center" width="33">Sangat Sering</th>
            </tr>
        </thead>
        <tbody>';
        if($hasil_test->num_rows() > 0)
        {
            $no = 1;
            foreach ($hasil_test->result() as $row)
            {
                echo '
                <tr>
                    <td>'.$no++.'</td>
                    <td>'.$row->pertanyaan.'</td>';
                    if($opsi_jawaban->num_rows() > 0)
                    {
                        foreach ($opsi_jawaban->result() as $opsi)
                        {
                            if($row->jawaban == $opsi->jawaban_initial)
                            {
                                echo '<td class="text-center"><span class="opsi_selected"><strong class="text-success">'.$opsi->jawaban_initial.'</strong></span></td>';
                            }
                            else
                            {
                                echo '<td class="text-center"><span class="opsi_unselected text-secondary">'.$opsi->jawaban_initial.'</span></td>';
                            }
                        }
                    }
                    echo '
                </tr>';
            }
        }

        echo '
        </tbody>
    </table>
</div>';